<?php

namespace Drupal\kenny_girls_training\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class KennyGirlAddExercise extends FormBase {
  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The term storage.
   *
   * @var \Drupal\taxonomy\TermStorageInterface
   */
  protected $termStorage;

  /**
   * The vocabulary storage.
   *
   * @var \Drupal\taxonomy\VocabularyInterface
   */
  protected $vocabularyStorage;

  /**
   * The media storage.
   *
   * @var \Drupal\media\MediaInterface
   */
  protected $mediaStorage;

  /**
   * KennyExercisesForm constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, MessengerInterface $messenger) {
    $this->entityTypeManager = $entity_type_manager;
    $this->termStorage = $entity_type_manager->getStorage('taxonomy_term');
    $this->vocabularyStorage = $entity_type_manager->getStorage('taxonomy_vocabulary');
    $this->mediaStorage = $entity_type_manager->getStorage('media');
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('messenger'),

    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'kenny_girl_exercises_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    /** @var \Drupal\taxonomy\TermStorageInterface $terms */
    $terms_body_plus_muscles = $this->termStorage->loadTree('girls_training', '0', '2');
    $terms_body = $this->termStorage->loadTree('girls_training', '0', '1');

    $compareFunction = function ($a, $b) {
      return $a->tid - $b->tid;
    };
    $terms_muscle = array_udiff($terms_body_plus_muscles, $terms_body, $compareFunction);

    $term_options = [];
    foreach ($terms_muscle as $term) {
      if ($term->name == 'Glutes Container') {
        $term->name = 'Glutes';
      }
      $term_options[$term->tid] = $term->name;
    }

    $form['term'] = [
      '#type' => 'select',
      '#title' => $this->t('Category'),
      '#options' => $term_options,
      '#empty_options' => '- Select -',
      '#required' => TRUE,
    ];

    $form['exercise_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Exercise Name'),
      '#max' => '100',
      '#required' => TRUE,
      '#suffix' => '<div class="exercise-item error" id="exercise_name"></div>',
    ];

    $form['exercise_video'] = [
      '#type' => 'file',
      '#title' => $this->t('Add a short video'),
      '#description' => $this->t('Must be a type /mp4'),
      '#required' => TRUE,
      '#upload_validators' => [
        'file_validate_extensions' => ['mp4'], // Розширення файлів, які приймаються.
      ],
      '#suffix' => '<div class="exercise-item error" id="exercise_video"></div>',
    ];


    $form['actions'] = [
      '#type' => 'button',
      '#value' => $this->t('Save'),
      '#ajax' => [
        'callback' => '::submitData'
      ],
      '#attributes' => [
        'class' => ['save-new-training-form', 'new-training-form-button'],
      ]
    ];

    return $form;
  }

  /**
   *  {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {


  }

  /**
   * The ajax submit.
   *
   * @param array $form
   *   The form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state
   * @return \Drupal\Core\Ajax\AjaxResponse
   * @throws \Drupal\Core\Entity\EntityStorageException
   *
   */
  function submitData(array &$form, FormStateInterface $form_state) {

    $ajax_response = new AjaxResponse();

    $flag = TRUE;
    // Валідація для exercise_name.
    $exercise_name = $form_state->getValue('exercise_name');
    if (mb_strlen($exercise_name) > 70) {
      return $ajax_response->addCommand(new HtmlCommand('#exercise_name', 'The exercise name must be 70 characters or less.'));
      $flag = FALSE;
    }

    if ($flag) {
      // Отримайте значення поля для завантаження файлу.
      $validators['file_validate_extensions'] = ['mp4'];
      $replace = FileSystemInterface::EXISTS_RENAME;

      // Upload file
      $file = file_save_upload('exercise_video', $validators, FALSE, 0, $replace);
      if (!$file) {
        return $ajax_response->addCommand(new HtmlCommand('#exercise_video', 'Video must be in MP4 format.'));
      }
      if ($file) {
        $file->save();
        $file_id = $file->id();

        // Create media.
        $media = $this->mediaStorage->create([
          'bundle' => 'video',
          'name' => $form_state->getValue('exercise_name'),
          'field_media_video_file' => [
            'target_id' => $file_id,
          ],
        ]);

        $media->save();


        // Get muscle id. Will be a parent for a new term.
        $term_id = $form_state->getValue('term');

        // Her exercise name
        $exercise_name = $form_state->getValue('exercise_name');
        $new_term = $this->termStorage->create([
          'vid' => 'girls_training',
          'name' => $exercise_name,
        ]);

        // Set parent
        $new_term->set('parent', [$term_id]);


        // Set video for term.
        $new_term->set('field_video_training', [
          'target_id' => $media->id(),
        ]);

        $new_term->save();

        $referer = $this->getRequest()->headers->get('referer');
        $ajax_response->addCommand(new RedirectCommand($referer));
      }
    }

    return $ajax_response;

  }
}