<?php

namespace App\customHelpers;

trait Myhelpers
{
    protected function baldeDatePicker($pickerDate) {
      $temp = explode("/", $pickerDate); 
      $formated = $temp[2] . '-' . $temp[1] . '-' . $temp[0];
      
      return $formated;
    }
}