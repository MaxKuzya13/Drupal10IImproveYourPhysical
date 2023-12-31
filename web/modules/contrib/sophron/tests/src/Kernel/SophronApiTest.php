<?php

declare(strict_types=1);

namespace Drupal\Tests\sophron\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\sophron\Map\DrupalMap;
use Drupal\sophron\MimeMapManagerInterface;
use FileEye\MimeMap\MalformedTypeException;
use FileEye\MimeMap\Map\DefaultMap;
use FileEye\MimeMap\MappingException;

/**
 * Tests for Sophron API.
 *
 * @coversDefaultClass \Drupal\sophron\MimeMapManager
 *
 * @group sophron
 */
class SophronApiTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['sophron'];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->installConfig(['sophron']);
  }

  /**
   * @covers ::getMapClass
   * @covers ::setMapClass
   * @covers ::listExtensions
   * @covers ::getExtension
   */
  public function testGetExtension(): void {
    $manager = \Drupal::service('sophron.mime_map.manager');
    $this->assertEquals(DrupalMap::class, $manager->getMapClass());
    $this->assertContains('atomsrv', $manager->listExtensions());
    $this->assertEquals('application/atomserv+xml', $manager->getExtension('atomsrv')->getDefaultType());
    // No type for extension.
    $manager->setMapClass(DefaultMap::class);
    $this->expectException(MappingException::class);
    $manager->getExtension('atomsrv')->getDefaultType();
  }

  /**
   * @covers ::listTypes
   * @covers ::getType
   */
  public function testGetType(): void {
    $manager = \Drupal::service('sophron.mime_map.manager');
    $this->assertContains('application/atomserv+xml', $manager->listTypes());
    $this->assertEquals(['atomsrv'], $manager->getType('application/atomserv+xml')->getExtensions());
  }

  /**
   * @covers ::getType
   */
  public function testGetMissingType(): void {
    $manager = \Drupal::service('sophron.mime_map.manager');
    // No extensions for type.
    $this->expectException(MappingException::class);
    $manager->getType('a/b')->getExtensions();
  }

  /**
   * @covers ::getType
   */
  public function testGetMalformedType(): void {
    $manager = \Drupal::service('sophron.mime_map.manager');
    // Malformed MIME type.
    $this->expectException(MalformedTypeException::class);
    $manager->getType('application/');
  }

  /**
   * @covers ::getMapClass
   * @covers ::getMappingErrors
   */
  public function testGetMappingErrors(): void {
    $config = \Drupal::configFactory()->getEditable('sophron.settings');
    $config
      ->set('map_option', MimeMapManagerInterface::DEFAULT_MAP)
      ->set('map_commands', [
        [
          'method' => 'aaa',
          'arguments' => ['paramA', 'paramB'],
        ],
        [
          'method' => 'bbb',
          'arguments' => ['paramC', 'paramD'],
        ],
        [
          'method' => 'ccc',
          'arguments' => ['paramE'],
        ],
        [
          'method' => 'ddd',
          'arguments' => [],
        ],
      ])
      ->save();
    $manager = \Drupal::service('sophron.mime_map.manager');
    $this->assertSame(DefaultMap::class, $manager->getMapClass());
    $this->assertCount(4, $manager->getMappingErrors(DefaultMap::class));
  }

}
