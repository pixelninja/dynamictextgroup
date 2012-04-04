<?php

	class Textgroup {
		
		public static function createNewTextGroup($element, $fieldCount=2, $values=NULL, $class=NULL, $schema=NULL, $default=NULL) {
			// Additional classes
			$classes = array();
			if($class) {
				$classes[] = $class;
			}
			
			// Field creator
			$fields = '';
			
//			if($default == true) {
//				for ($i=0; $i<$fieldCount; $i++) {
//					
//					var_dump($schema[$i]->handle);
//					exit;
//
//					switch ($schema[$i]->options->type) {
//						case 'text':
//							$fields .= self::__createTextField($element, $schema[$i]->handle, $values, $schema[$i]->label, $schema[$i]->width, $schema[$i]->options->required);
//							break;
//						case 'select':
//							$fields .= self::__createSelectField($element, $schema[$i]->handle, $fieldVal, $schema[$i]->label, $schema[$i]->width, $schema[$i]->options);
//							break;
//						case 'checkbox':
//							$fields .= self::__createCheckboxField($element, $schema[$i]->handle, $fieldVal, $schema[$i]->label, $schema[$i]->width, $schema[$i]->options);
//							break;
//						case 'radio':
//							$fields .= self::__createRadioField($element, $schema[$i]->handle, $fieldVal, $schema[$i]->label, $schema[$i]->width, $schema[$i]->options);
//							break;
//					}
//				}
//			} else {	
			if($default == true) {
				for ($i=0; $i<$fieldCount; $i++) {
					if ($schema[$i]->options->type == 'text') {
						if ($schema[$i]->handle == 'title') {
							$fields .= self::__createTextField($element, $schema[$i]->handle, $values, $schema[$i]->label, $schema[$i]->width, $schema[$i]->options->required, $readonly = true);
						} else {
							$fields .= self::__createTextField($element, $schema[$i]->handle, $fieldVal, $schema[$i]->label, $schema[$i]->width, $schema[$i]->options->required);
						}
					}
					if ($schema[$i]->options->type == 'select') {
						
						if ($element == "financial-summary") {
							$weeks = 'Weeks';
							$hours = 'Hours';
							$dollars = 'Dollars';
							
							$management = 'Management';
							$design = 'Design';
							$development = 'Development';
							$materials = 'Materials';
							
							if ($i == '2') {
								if ($values == "Target Duration") $fieldVal = $weeks;
								elseif ($values == "Actual Duration") $fieldVal = $weeks;
								elseif ($values == "Material Budget") $fieldVal = $dollars;
								elseif ($values == "Material Costs") $fieldVal = $dollars;
								else $fieldVal = $hours;
							}
							
							if ($i == '3') {
								if ($values == "Target Duration") $fieldVal = '';
								elseif ($values == "Actual Duration") $fieldVal = '';
								elseif ($values == "PM Hrs Budgeted") $fieldVal = $management;
								elseif ($values == "PM Hrs Actual") $fieldVal = $management;
								elseif ($values == "ACD Hrs Budgeted") $fieldVal = $management;
								elseif ($values == "ACD Hrs Actual") $fieldVal = $management;
								elseif ($values == "Dev Time Budgeted") $fieldVal = $development;
								elseif ($values == "Dev Time Actual") $fieldVal = $development;
								elseif ($values == "Material Budget") $fieldVal = $materials;
								elseif ($values == "Material Costs") $fieldVal = $materials;
								else $fieldVal = $design;
							}
						}
						$fields .= self::__createSelectField($element, $schema[$i]->handle, $fieldVal, $schema[$i]->label, $schema[$i]->width, $schema[$i]->options);
					}
					if ($schema[$i]->options->type == 'checkbox') {
						$fields .= self::__createCheckboxField($element, $schema[$i]->handle, $fieldVal, $schema[$i]->label, $schema[$i]->width, $schema[$i]->options);
					}
					if ($schema[$i]->options->type == 'radio') {
						$fields .= self::__createRadioField($element, $schema[$i]->handle, $fieldVal, $schema[$i]->label, $schema[$i]->width, $schema[$i]->options);
					}
				}
			} else {	
				for ($i=0; $i<$fieldCount; $i++) {
					$fieldVal = ($values != NULL && $values[$i] != ' ') ? $values[$i] : NULL;
					
					if ($schema[$i]->options->type == 'text') {
						if ($schema[$i]->handle == 'title') {
							$fields .= self::__createTextField($element, $schema[$i]->handle, $fieldVal, $schema[$i]->label, $schema[$i]->width, $schema[$i]->options->required, $readonly = true);
						} else {
							$fields .= self::__createTextField($element, $schema[$i]->handle, $fieldVal, $schema[$i]->label, $schema[$i]->width, $schema[$i]->options->required);
						}
					}
					if ($schema[$i]->options->type == 'select') {
						$fields .= self::__createSelectField($element, $schema[$i]->handle, $fieldVal, $schema[$i]->label, $schema[$i]->width, $schema[$i]->options);
					}
					if ($schema[$i]->options->type == 'checkbox') {
							$fields .= self::__createCheckboxField($element, $schema[$i]->handle, $fieldVal, $schema[$i]->label, $schema[$i]->width, $schema[$i]->options);
					}
					if ($schema[$i]->options->type == 'radio') {
							$fields .= self::__createRadioField($element, $schema[$i]->handle, $fieldVal, $schema[$i]->label, $schema[$i]->width, $schema[$i]->options);
					}

//					switch ($schema[$i]->options->type) {
//						case 'text':
//							$fields .= self::__createTextField($element, $schema[$i]->handle, $fieldVal, $schema[$i]->label, $schema[$i]->width, $schema[$i]->options->required);
//							break;
//						case 'select':
//							$fields .= self::__createSelectField($element, $schema[$i]->handle, $fieldVal, $schema[$i]->label, $schema[$i]->width, $schema[$i]->options);
//							break;
//						case 'checkbox':
//							$fields .= self::__createCheckboxField($element, $schema[$i]->handle, $fieldVal, $schema[$i]->label, $schema[$i]->width, $schema[$i]->options);
//							break;
//						case 'radio':
//							$fields .= self::__createRadioField($element, $schema[$i]->handle, $fieldVal, $schema[$i]->label, $schema[$i]->width, $schema[$i]->options);
//							break;
//					}
				}
			}
						
			// Create element
			return new XMLElement(
				'li', 
				'<span>
					<span class="fields">' . $fields . '<div class="clear"></span>
				</span>', 
				array('class' => implode($classes, ' '))
			);
		}
		
		private static function __createTextField($element, $handle, $textvalue, $label=NULL, $width=NULL, $required=NULL, $readonly=NULL) {
			// Generate text field
			$width = 'style="width:'. $width .'% !important;"';
			$reqLabelAppendage = $required ? ' <span class="req">*</span>' : '';
			$reqclas .= $required ? ' req' : '';
			$lbl = '<label style="display:none;" for="fields[' . $element . '][' . $handle . '][]">' . $label . $reqLabelAppendage . '</label>';
			if ($readonly == true) {
				return '<span class="fieldHolder '. $handle .'-holder'.$reqclas.'" '. $width .'>'. $lbl .'<input type="text" id="field-'. $handle .'" name="fields['. $element .']['. $handle .'][]" value="'. $textvalue .'" placeholder="'. $label .'" class="field-'. $handle .'" readonly="true" /></span>';
			} else {
				return '<span class="fieldHolder '. $handle .'-holder'.$reqclas.'" '. $width .'>'. $lbl .'<input type="text" id="field-'. $handle .'" name="fields['. $element .']['. $handle .'][]" value="'. $textvalue .'" placeholder="'. $label .'" class="field-'. $handle .'" /></span>';
			}
		}
		
		private static function __createSelectField($element, $handle, $val, $label=NULL, $width=NULL, $options=NULL) {
			// Generate select list
			$reqLabelAppendage = $options->required ? ' <span class="req">*</span>' : '';
			$reqclas .= $options->required ? ' req' : '';
			if ($val == NULL)  $class .= ' empty';
			$fSelectItems = explode(',', $options->selectItems);
			$width = 'style="width:'. $width .'% !important;"';
			$select = '<span class="fieldHolder '. $handle .'-holder'. $reqclas .'" '. $width .'>';
			$select .= '<label style="display:none;" for="fields[' . $element . '][' . $handle . '][]">' . $label . $reqLabelAppendage . '</label>';
			$select .= '<select id="field-'. $handle .'" name="fields['. $element .']['. $handle .'][]" class="styled field-'. $handle .'">';
			$select .= '<option value="">'. $label .'</option>';
			//$select .= '<optgroup label="'. $label .'">';
			$select .= '<optgroup label="Select one:">';
			foreach ($fSelectItems as &$item) {
				$item = trim($item);
				$selected = $val == $item ? 'selected="selected"' : '';
				$select .= '<option '. $selected .'>'. $item .'</option>';
			}
			$select .= '</optgroup></select></span>';
			return $select;
		}
		
		private static function __createCheckboxField($element, $handle, $val, $label=NULL, $width=NULL, $options=NULL) {
			// Generate radio button field
			$width = 'style="width:'. $width .'% !important;"';
			$checked = $val == 'yes' ? 'checked="checked"' : '';
			$field = '<span class="fieldHolder fieldtype-checkbox '. $handle .'-holder" '. $width .'>';
			$field .= '<label for="'. $handle .'-checker" class="fieldtype-checkbox-label"><input type="checkbox" name="'. $handle .'-checker" '. $checked .' /> '. $label .'</label>';
			$field .= '<input type="hidden" id="field-'. $handle .'" name="fields['. $element .']['. $handle .'][]" value="'. $val .'" />';
			$field .= '</span>';
			return $field;
		}
		
		private static function __createRadioField($element, $handle, $val, $label=NULL, $width=NULL, $options=NULL) {
			// Generate radio button field
			$width = 'style="width:'. $width .'% !important;"';
			$checked = $val == 'yes' ? 'checked="checked"' : '';
			$field = '<span class="fieldHolder fieldtype-radio '. $handle .'-holder" '. $width .'>';
			$field .= '<label for="'. $handle .'-checker" class="fieldtype-radio-label"><input type="radio" name="'. $handle .'-checker" '. $checked .' /> '. $label .'</label>';
			$field .= '<input type="hidden" id="field-'. $handle .'" name="fields['. $element .']['. $handle .'][]" value="'. $val .'" />';
			$field .= '</span>';
			return $field;
		}
	}
	