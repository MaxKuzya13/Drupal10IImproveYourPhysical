<?php declare(strict_types=1);

namespace Drupal\sophron_guesser;

use Drupal\Core\File\FileSystemInterface;
use Drupal\sophron\MimeMapManagerInterface;
use FileEye\MimeMap\MappingException;
use Symfony\Component\Mime\MimeTypeGuesserInterface;

/**
 * Makes possible to guess the MIME type of a file using its extension.
 */
class SophronMimeTypeGuesser implements MimeTypeGuesserInterface {

  /**
   * Constructs a SophronMimeTypeGuesser object.
   *
   * @param \Drupal\sophron\MimeMapManagerInterface $mimeMapManager
   *   The MIME map manager service.
   * @param \Drupal\Core\File\FileSystemInterface $fileSystem
   *   The file system service.
   */
  public function __construct(
    protected MimeMapManagerInterface $mimeMapManager,
    protected FileSystemInterface $fileSystem
  ) {}

  /**
   * {@inheritdoc}
   */
  public function guessMimeType(string $path): string {
    $extension = '';
    $file_parts = explode('.', $this->fileSystem->basename($path));

    // Remove the first part: a full filename should not match an extension.
    array_shift($file_parts);

    // Iterate over the file parts, trying to find a match.
    // For 'my.awesome.image.jpeg', we try: 'jpeg', then 'image.jpeg', then
    // 'awesome.image.jpeg'.
    while ($additional_part = array_pop($file_parts)) {
      $extension = strtolower($additional_part . ($extension ? '.' . $extension : ''));
      if ($mime_map_extension = $this->mimeMapManager->getExtension($extension)) {
        try {
          return $mime_map_extension->getDefaultType();
        }
        catch (MappingException $e) {
          return 'application/octet-stream';
        }
      }
    }

    return 'application/octet-stream';
  }

  /**
   * {@inheritdoc}
   */
  public function isGuesserSupported(): bool {
    return TRUE;
  }

  /**
   * Sets the mimetypes/extension mapping to use when guessing mimetype.
   *
   * This method is implemented to ensure that when this class is set to
   * override \Drupal\Core\File\MimeType\ExtensionMimeTypeGuesser in the service
   * definition, any call to this method does not fatal. Actually, for Sophron
   * this is a no-op.
   *
   * @param array|null $mapping
   *   Not relevant.
   */
  public function setMapping(?array $mapping = NULL) {
    // Do nothing.
  }

}
