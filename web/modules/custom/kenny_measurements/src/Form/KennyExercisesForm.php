<?php

namespace Drupal\kenny_measurements\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class KennyExercisesForm extends FormBase {

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
    return 'kenny_exercises_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    /** @var \Drupal\taxonomy\TermStorageInterface $terms */
    $terms = $this->termStorage->loadTree('body_part');
    $term_options = ['' => $this->t('- Select -')];
    foreach ($terms as $term) {
      $term_options[$term->tid] = $term->name;
    }

    $form['term'] = [
      '#type' => 'select',
      '#title' => $this->t('Category'),
      '#options' => $term_options,
      '#required' => TRUE,
    ];

    $form['exercise_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Exercise Name'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }


  /**
   *  {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Отримуємо айді терміна який вибрали в select
    $term_id = $form_state->getValue('term');
    // Отримуємо назву вправи яку хочемо додати
    $exercise = $form_state->getValue('exercise_name');

    /** @var \Drupal\taxonomy\TermStorageInterface $term */
    $term = $this->termStorage->load($term_id);
    // Так як ми отримували id терміна із Vocabulary Body part, то нам тепер
    // треба отримати сам vocabulary для цього терміна, а він в нас має таку
    // назву, тільки з маленької бувки.
    $vocabulary_name = strtolower($term->getName());

    /** @var \Drupal\taxonomy\VocabularyInterface $body_part */
    $body_part = $this->vocabularyStorage->load($vocabulary_name);
    if ($body_part) {
      // Перевіряємо чи існує вже вправа з такою назвою як у нас

        $query = $this->termStorage->getQuery()
          ->condition('vid', $body_part->id())
          ->condition('name', $exercise)
          ->accessCheck(FALSE)
          ->range(0,1);

      $tids = $query->execute();

      // Якщо таких вправ нема, то додаємо
      if (empty($tids)) {
        $new_exercise = $this->termStorage->create([
          'vid' => $body_part->id(),
          'name' => $exercise,
        ]);
        $new_exercise->save();

        // Виводимо текст допоміжний
        $this->messenger->addMessage(
          t('The exercise "@exercise" has been added to the body part "@body_part"', [
            '@exercise' => $exercise, '@body_part' => $term->getName()
            ])
        );
      } else {
        $this->messenger->addMessage(
          t('The exercise "@exercise" already exists in the body part "@body_part"', [
            '@exercise' => $exercise, '@body_part' => $term->getName()
          ])
        );
      }

    } else {
      $this->messenger->addMessage(
        t('The body part "@body_part" does not exist.', ['@body_part' => $term->getName()])
      );
    }

  }


}