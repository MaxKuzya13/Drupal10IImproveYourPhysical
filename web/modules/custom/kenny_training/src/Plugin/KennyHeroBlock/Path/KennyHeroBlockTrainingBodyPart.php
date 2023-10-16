<?php

namespace Drupal\kenny_training\Plugin\KennyHeroBlock\Path;

use Drupal\kenny_hero_block\Plugin\KennyHeroBlock\Path\KennyHeroBlockPathPluginBase;
use Drupal\media\MediaInterface;
use Symfony\Component\DependencyInjection\Container;

/**
 * Hero block for training path for each body part
 *
 * @KennyHeroBlockPath(
 *   id = "kenny_hero_block_training_body_part",
 *   match_type = "listed",
 *   match_path = {"/training/*"},
 * )
 */

class KennyHeroBlockTrainingBodyPart extends KennyHeroBlockPathPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getHeroTitle() {
    return Container::camelize($this->getCurrentPath());
  }

  /**
   * {@inheritdoc}
   */
  public function getHeroSubtitle() {
    $body_part = $this->getCurrentPath();
    switch ($body_part) {
      case 'back':
        $subtitle = "The back, also known as the vertebral column, is a remarkable anatomical structure that plays a crucial role in supporting the body, protecting the spinal cord, and enabling various movements. It consists of 33 vertebrae, which can be categorized into five regions: cervical, thoracic, lumbar, sacral, and coccygeal. The spine's natural curves – cervical and lumbar lordosis, and thoracic and sacral kyphosis – contribute to its flexibility and shock-absorbing capabilities. Between the vertebrae are intervertebral discs, which provide cushioning and facilitate movement. The spine also houses the spinal cord, a vital part of the central nervous system. Proper care and attention to spinal health are essential to maintain mobility and overall well-being.";
        break;
      case 'chest':
        $subtitle = "The thoracic region, also known as the chest or thorax, is a vital part of human anatomy. It spans from the base of the neck to the diaphragm and contains various essential structures. The ribcage, composed of twelve pairs of ribs, encases and protects vital organs, including the heart and lungs. The sternum, or breastbone, serves as the central support for the ribcage. Within the thoracic cavity, the heart orchestrates circulation, while the lungs facilitate respiration. The thoracic spine, or upper part of the vertebral column, provides stability and flexibility. Together, these components in the thoracic region ensure the proper functioning of the circulatory and respiratory systems.";
        break;
      case 'biceps':
        $subtitle = "The biceps brachii, often referred to as the biceps, is a prominent muscle in the upper arm. It consists of two heads – the long head and the short head – that connect to the scapula and the humerus, respectively. The primary function of the biceps is to flex the elbow joint, allowing for movements like lifting and curling the forearm. Additionally, it aids in supination, which involves turning the palm upwards. The biceps not only plays a crucial role in arm strength but also contributes to the aesthetics of the upper arm. Regular exercise and proper care are essential for maintaining bicep health and function.";
        break;
      case 'triceps':
        $subtitle = "The triceps brachii, commonly known as the triceps, is a significant muscle group located on the back of the upper arm. It comprises three heads – the long head, lateral head, and medial head. The primary function of the triceps is to extend the elbow joint, which allows for straightening the arm. This muscle is crucial for movements like pushing, lifting, and stabilizing the arm during various activities. Well-developed triceps contribute to the overall strength and aesthetics of the upper arm. Engaging in targeted triceps exercises, like tricep dips and tricep extensions, is essential for building and maintaining muscle strength in this area.";
        break;
      case 'shoulders':
        $subtitle = "The shoulder joint is one of the most complex and mobile joints in the human body. It consists of three main components: the clavicle, scapula, and the upper part of the arm. The primary function of this joint is to provide a wide range of motion in various directions. With a multitude of muscles and ligaments, the shoulder joint enables rotation, lifting, and lowering of the arm, as well as providing essential stability for various activities. It's crucial to care for this joint, as injuries or issues with it can lead to significant movement limitations and pain.";
        break;
      case 'legs':
        $subtitle = "The lower extremities, commonly referred to as the legs, are a crucial part of human anatomy. They consist of several key components, including the thigh, calf, ankle, and foot. The femur, or thigh bone, is the longest and strongest bone in the human body, connecting the hip to the knee. The calf muscle, comprising the gastrocnemius and soleus muscles, facilitates movements like walking and running, and it's anchored to the Achilles tendon. The ankle and foot are intricate structures containing numerous bones, ligaments, and tendons, enabling balance and various movements. Understanding the anatomy of the lower limbs is essential for maintaining mobility, stability, and overall physical well-being.";
        break;
      default:
        $subtitle = '';
    }
    return $subtitle;
  }

  /**
   * {@inheritdoc}
   */
  public function getHeroImage() {
    $last_segment = $this->getCurrentPath();

    $media_storage = $this->getEntityTypeManager()->getStorage('media');
    switch ($last_segment) {
      case 'back':
        $media_image = $media_storage->load(3);
        break;
      case 'chest':
        $media_image = $media_storage->load(4);
        break;
      case 'biceps':
        $media_image = $media_storage->load(5);
        break;
      case 'triceps':
        $media_image = $media_storage->load(6);
        break;
      case 'shoulders':
        $media_image = $media_storage->load(7);
        break;
      case 'legs':
        $media_image = $media_storage->load(8);
        break;
    }

    if ($media_image instanceof MediaInterface) {
      return $media_image->get('field_media_image')->entity->get('uri')->value;
    }
  }

  protected function getCurrentPath() {
    $current_path = \Drupal::request()->getRequestUri();
    $path_segments = explode('/', trim($current_path, '/'));
    $last_segment = end($path_segments);
    return $last_segment;
  }

}