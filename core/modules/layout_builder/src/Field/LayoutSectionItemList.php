<?php

namespace Drupal\layout_builder\Field;

use Drupal\Core\Field\FieldItemList;
use Drupal\layout_builder\Section;
use Drupal\layout_builder\SectionStorageInterface;

/**
 * Defines a item list class for layout section fields.
 *
 * @internal
 *
 * @see \Drupal\layout_builder\Plugin\Field\FieldType\LayoutSectionItem
 */
class LayoutSectionItemList extends FieldItemList implements SectionStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function insertSection($delta, Section $section) {
    if ($this->get($delta)) {
      /** @var \Drupal\layout_builder\Plugin\Field\FieldType\LayoutSectionItem $item */
      $item = $this->createItem($delta);
      $item->section = $section;

      $start = array_slice($this->list, 0, $delta);
      $end = array_slice($this->list, $delta);
      $this->list = array_merge($start, [$item], $end);
    }
    else {
      $this->appendSection($section);
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function appendSection(Section $section) {
    $this->appendItem()->section = $section;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSections() {
    $sections = [];
    /** @var \Drupal\layout_builder\Plugin\Field\FieldType\LayoutSectionItem $item */
    foreach ($this->list as $delta => $item) {
      $sections[$delta] = $item->section;
    }
    return $sections;
  }

  /**
   * {@inheritdoc}
   */
  public function getSection($delta) {
    /** @var \Drupal\layout_builder\Plugin\Field\FieldType\LayoutSectionItem $item */
    if (!$item = $this->get($delta)) {
      throw new \OutOfBoundsException(sprintf('Invalid delta "%s" for the "%s" entity', $delta, $this->getEntity()->label()));
    }

    return $item->section;
  }

  /**
   * {@inheritdoc}
   */
  public function removeSection($delta) {
    $this->removeItem($delta);
    return $this;
  }

}
