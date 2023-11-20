<?php

namespace Drupal\kenny_training\Event;


use Drupal\Component\EventDispatcher\Event;
use Drupal\Core\Entity\EntityInterface;


class HelloWorldControllerEvent extends Event {

  /**
   * The page content.
   *
   * @var array
   */
  protected array $pageContent;

  /**
   * The page title.
   *
   * @var string|null
   */
  protected ?string $pageTitle;

  /**
   * Construct a new HelloWorldControllerEvent object.
   *
   * @param array $page_content
   *   The page content.
   * @param string|NULL $page_title
   *   The page title.
   */
  public function __construct(array $page_content, string $page_title = NULL) {
    $this->setPageContent($page_content);
    $this->setPageTitle($page_title);
  }

  /**
   * @param array $page_content
   * @return $this
   */
  public function setPageContent(array $page_content) {
    $this->pageContent = $page_content;

    return $this;
  }

  /**
   * @return $this
   */
  public function getPageContent() {
    return $this->pageContent;

  }

  /**
   * @param string $page_title
   * @return $this
   */
  public function setPageTitle(string $page_title = NULL) {
    $this->pageTitle = $page_title;

    return $this;
  }

  /**
   * @return $this
   */
  public function getPageTitle() {

    return $this->pageTitle;

  }
}