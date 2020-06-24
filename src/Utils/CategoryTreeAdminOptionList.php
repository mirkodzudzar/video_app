<?php

namespace App\Utils;

use App\Utils\AbstractClasses\CategoryTreeAbstract;

class CategoryTreeAdminOptionList extends CategoryTreeAbstract {

  public $html_1 = '<select class="custom-select mr-sm-2" id="inlineFormCustomSelect">';
  public $html_2 = '<option value="';
  public $html_3 = '">';
  public $html_4 = '</option>';
  public $html_5 = '</select>';

  public function getCategoryList(array $categories_array, int $repeat = 0) {

    foreach ($categories_array as $value) {
      $this->categoryList[] = ['name' => str_repeat("-", $repeat) . $value['name'], 'id' => $value['id']];

      if (!empty($value['children'])) {
        $repeat = $repeat + 2;
        $this->getCategoryList($value['children'], $repeat);
        $repeat = $repeat - 2;
      }
    }

    return $this->categoryList;
  }

}