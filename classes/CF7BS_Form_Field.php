<?php
/**
 * CF7BS_Form_Field class
 *
 * @package CF7BS
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 * @since 1.0.0
 */

class CF7BS_Form_Field extends CF7BS_Component {
	public function display( $echo = true ) {
		$output = apply_filters( 'cf7bs_bootstrap_form_field_display', '', $this->args );

		if ( empty( $output ) ) {
			$output = '';

			extract( $this->args );

			$type = $this->validate_type( $type );

			$value = $this->validate_value( $value, $type, $options );

			if ( is_numeric($tabindex) ) {
				$tabindex = intval($tabindex);
			}

			if ( 'hidden' != $type ) {
				if ( ! empty( $label_class ) ) {
					$label_class .= ' ';
				}

				$input_div_class = '';
				$input_class = $class;
				if ( 'horizontal' == $form_layout ) {
					$label_class .= ' control-label';
					$classes = $this->get_column_width_classes( $form_label_width, $form_breakpoint, $grid_columns );
					$label_class .= ' ' . $classes['label'];
					$input_div_class = $classes['input'];
					if ( empty( $label ) ) {
						$input_div_class .= ' ' . $this->get_column_offset_class( $form_label_width, $form_breakpoint, $grid_columns );
					}
				} elseif( 'inline' == $form_layout ) {
					$label_class .= ' sr-only';
					if ( empty( $placeholder ) ) {
						$placeholder = $label;
					}
				}

				if ( ! empty( $wrapper_class ) ) {
					$wrapper_class = ' ' . esc_attr( $wrapper_class );
				}

				if ( ! in_array( $type, array( 'radio', 'checkbox' ) ) ) {
					if ( ! empty( $input_class ) ) {
						$input_class .= ' ';
					}
					if ( ! in_array( $type, array( 'file', 'range' ) ) ) {
						$input_class .= 'form-control';
					}

					if ( 'textarea' != $type ) {
						if ( 'large' == $size ) {
							$input_class .= ' input-lg';
						} elseif ( in_array( $size, array( 'small', 'mini' ) ) ) {
							$input_class .= ' input-sm';
						}
					}

					if ( is_int( $tabindex ) ) {
						$tabindex = ' tabindex="' . $tabindex . '"';
					} else {
						$tabindex = '';
					}
				}

				if ( ! empty( $input_class ) ) {
					$input_class = ' class="' . esc_attr( $input_class ) . '"';
				}
				if ( ! empty( $placeholder ) ) {
					$placeholder = ' placeholder="' . esc_attr( $placeholder ) . '"';
				}

				if ( $readonly ) {
					$readonly = ' readonly';
				} else {
					$readonly = '';
				}

				if ( $minlength && $minlength > 0 ) {
					$minlength = ' minlength="' . absint( $minlength ) . '"';
				} else {
					$minlength = '';
				}

				if ( $maxlength && $maxlength > -1 ) {
					$maxlength = ' maxlength="' . absint( $maxlength ) . '"';
				} else {
					$maxlength = '';
				}

				$append = '';

				if ( in_array( $status, array( 'success', 'warning', 'error' ) ) ) {
					$status = ' has-' . $status;
				} else {
				  $status = '';
				}

				if ( 'has-error' == $status ) {
					$append .= ' aria-invalid="true"';
				} else {
					$append .= ' aria-invalid="false"';
				}

				$label_required = '';
				$required = 'required' == $mode || version_compare( WPCF7_VERSION, '4.9', '>=' ) && 'radio' == $type;
				if ( $required ) {
					$append .= ' aria-required="true" required';
					$label_required = ' ' . cf7bs_get_form_property( 'required_html' );
				}
				if ( 'disabled' == $mode ) {
					$append .= ' disabled';
				}

				if ( 'none' != $form_layout ) {
					if ( 'horizontal' == $form_layout ) {
						$output .= '<div class="form-group' . $wrapper_class . $status . '">';
						if ( ! empty( $label ) ) {
							$output .= '<label class="' . esc_attr( $label_class ) . '"' . ( ! empty( $id ) ? ' for="' . esc_attr( $id ) . '"' : '' ) . '>' . wp_kses( $label, 'cf7bs_form_label' ) . $label_required . '</label>';
						}
						$output .= '<div class="' . esc_attr( $input_div_class ) . '">';
					} elseif( 'inline' == $form_layout ) {
						$output .= '<div class="form-group' . $wrapper_class . $status . '">';
						if ( ! empty( $label ) ) {
							$output .= '<label class="' . esc_attr( $label_class ) . '"' . ( ! empty( $id ) ? ' for="' . esc_attr( $id ) . '"' : '' ) . '>' . wp_kses( $label, 'cf7bs_form_label' ) . $label_required . '</label>';
						}
					} else {
						$output .= '<div class="form-group' . $wrapper_class . $status . '">';
						if ( ! empty( $label ) ) {
							$rc_group_style = '';
							if ( in_array( $type, array( 'radio', 'checkbox' ) ) ) {
								$rc_group_style = ' style="display:block;"';
							}
							$output .= '<label class="' . esc_attr( $label_class ) . '"' . ( ! empty( $id ) ? ' for="' . esc_attr( $id ) . '"' : '' ) . $rc_group_style . '>' . wp_kses( $label, 'cf7bs_form_label' ) . $label_required . '</label>';
						}
					}
				}
			}

			switch ( $type ) {
				case 'checkbox':
					if ( count( $options ) <= 1 ) {
						$curval = key( $options );
						$title = $options[ $curval ];
						if ( false === strpos( $title, 'wpcf7-free-text' ) ) {
							$title = wp_kses( $title, 'cf7bs_form_label' );
						}

						$output .= '<div class="checkbox' . $wrapper_class . '">';
						$output .= '<label '. ( ! empty( $id ) ? ' for="' . esc_attr( $id ) . '"' : '' ) .'>' ;
						$output .= '<input' . $input_class . ( ! empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" type="checkbox" value="' . esc_attr( $curval ) . '"' . cf7bs_checked( $value, $curval, false ) . ( is_int( $tabindex ) ? ' tabindex="' . $tabindex . '"' : '' ) . $append . '>';
						$output .= $title;
						$output .= '</label>';
						$output .= '</div>';
					} else {
						if ( defined( 'CF7BS_FIELDSET_WRAP' ) && CF7BS_FIELDSET_WRAP ) {
							$output .= '<fieldset>';
						}
						if ( 'buttons' == $group_layout ) {
							$button_group = new CF7BS_Button_Group( array(
								'mode'		=> 'checkbox',
								'size'		=> $size,
							) );
							$output .= $button_group->open( false );
							$counter = 0;
							foreach ( $options as $curval => $title ) {
								$is_checked = cf7bs_multiple_checked( $value, $curval, false );
								$output .= $button_group->insert_button( array(
									'type'		=> $group_type,
									'id'		=> ! empty( $id ) ? $id . ( $counter + 1 ) : '',
									'name'		=> $name . '[]',
									'class'		=> $class,
									'value'		=> $curval,
									'title'		=> $title,
									'append'	=> ( is_int( $tabindex ) ? ' tabindex="' . ( $tabindex + $counter ) . '"' : '' ) . $is_checked . $append,
								), false );
								$counter++;
							}
							$output .= $button_group->close( false );
						} elseif ( 'inline' == $group_layout && 'inline' != $form_layout ) {
							$counter = 0;
							foreach ( $options as $curval => $title ) {
								if ( false === strpos( $title, 'wpcf7-free-text' ) ) {
									$title = wp_kses( $title, 'cf7bs_form_label' );
								}
								$output .= '<label class="checkbox-inline" ' . ( ! empty( $id ) ? ' for="' . esc_attr( $id . ( $counter + 1 ) ) . '"' : '' ) .'>';
								$output .= '<input' . $input_class . ( ! empty( $id ) ? ' id="' . esc_attr( $id . ( $counter + 1 ) ) . '"' : '' ) . ' name="' . esc_attr( $name . '[]' ) . '" type="checkbox" value="' . esc_attr( $curval ) . '"' . cf7bs_multiple_checked( $value, $curval, false ) . ( $tabindex >= 0 ? ' tabindex="' . ( $tabindex + $counter ) . '"' : '' ) . $append . '>';
								$output .= $title;
								$output .= '</label>';
								$counter++;
							}
						} else {
							$counter = 0;
							foreach ( $options as $curval => $title ) {
								if ( false === strpos( $title, 'wpcf7-free-text' ) ) {
									$title = wp_kses( $title, 'cf7bs_form_label' );
								}
								$output .= '<div class="checkbox">';
								$output .= '<label ' . ( ! empty( $id ) ? ' for="' . esc_attr( $id . ( $counter + 1 ) ) . '"' : '' ) . '>';
								$output .= '<input' . $input_class . ( ! empty( $id ) ? ' id="' . esc_attr( $id . ( $counter + 1 ) ) . '"' : '' ) . ' name="' . esc_attr( $name . '[]' ) . '" type="checkbox" value="' . esc_attr( $curval ) . '"' . cf7bs_multiple_checked( $value, $curval, false ) . ( is_int( $tabindex ) ? ' tabindex="' . ( $tabindex + $counter ) . '"' : '' ) . $append . '>';
								$output .= $title;
								$output .= '</label>';
								$output .= '</div>';
								$counter++;
							}
						}
						if ( defined( 'CF7BS_FIELDSET_WRAP' ) && CF7BS_FIELDSET_WRAP ) {
							$output .= '</fieldset>';
						}
					}
					break;
				case 'select':
					$output .= '<select' . $input_class . ( ! empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '"' . $tabindex . $append . '>';
					foreach ( $options as $curval => $title ) {
						$output .= '<option value="' . esc_attr( $curval ) . '"' . cf7bs_selected( $value, $curval, false ) . '>' . esc_html( $title ) . '</option>';
					}
					$output .= '</select>';
					break;
				case 'multiselect':
					$output .= '<select' . $input_class . ( ! empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' name="' . esc_attr( $name . '[]' ) . '" multiple' . $tabindex . $append . '>';
					foreach ( $options as $curval => $title ) {
						$output .= '<option value="' . esc_attr( $curval ) . '"' . cf7bs_multiple_selected( $value, $curval, false ) . '>' . esc_html( $title ) . '</option>';
					}
					$output .= '</select>';
					break;
				case 'radio':
					if ( count( $options ) <= 1 ) {
						$curval = key( $options );
						$title = $options[ $curval ];
						if ( false === strpos( $title, 'wpcf7-free-text' ) ) {
							$title = wp_kses( $title, 'cf7bs_form_label' );
						}
						$output .= '<div class="radio' . $wrapper_class . '">';
						$output .= '<label ' . ( ! empty( $id ) ? ' for="' . esc_attr( $id ) . '"' : '' ) . '>';
						$output .= '<input' . $input_class . ( ! empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" type="radio" value="' . esc_attr( $curval ) . '"' . cf7bs_checked( $value, $curval, false ) . ( is_int( $tabindex ) ? ' tabindex="' . $tabindex . '"' : '' ) . $append . '>';
						$output .= $title;
						$output .= '</label>';
						$output .= '</div>';
					} else {
						if ( defined( 'CF7BS_FIELDSET_WRAP' ) && CF7BS_FIELDSET_WRAP ) {
							$output .= '<fieldset>';
						}
						if ( 'buttons' == $group_layout ) {
							$button_group = new CF7BS_Button_Group( array(
								'mode'		=> 'radio',
								'size'		=> $size,
							) );
							$output .= $button_group->open( false );
							$counter = 0;
							foreach ( $options as $curval => $title ) {
								$is_checked = cf7bs_checked( $value, $curval, false );
								$output .= $button_group->insert_button( array(
									'type'		=> $group_type,
									'id'		=> ! empty( $id ) ? $id . ( $counter + 1 ) : '',
									'name'		=> $name,
									'class'		=> $class,
									'value'		=> $curval,
									'title'		=> $title,
									'append'	=> ( is_int( $tabindex ) ? ' tabindex="' . ( $tabindex + $counter ) . '"' : '' ) . $is_checked . $append,
								), false );
								$counter++;
							}
							$output .= $button_group->close( false );
						} elseif( 'inline' == $group_layout && 'inline' != $form_layout ) {
							$counter = 0;
							foreach ( $options as $curval => $title ) {
								if ( false === strpos( $title, 'wpcf7-free-text' ) ) {
									$title = wp_kses( $title, 'cf7bs_form_label' );
								}
								$output .= '<label class="radio-inline"' . ( ! empty( $id ) ? ' for="' . esc_attr( $id . ( $counter + 1 ) ) . '"' : '' ) . '>';
								$output .= '<input' . $input_class . ( ! empty( $id ) ? ' id="' . esc_attr( $id . ( $counter + 1 ) ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" type="radio" value="' . esc_attr( $curval ) . '"' . cf7bs_checked( $value, $curval, false ) . ( $tabindex >= 0 ? ' tabindex="' . ( $tabindex + $counter ) . '"' : '' ) . $append . '>';
								$output .= $title;
								$output .= '</label>';
								$counter++;
							}
						} else {
							$counter = 0;
							foreach ( $options as $curval => $title ) {
								if ( false === strpos( $title, 'wpcf7-free-text' ) ) {
									$title = wp_kses( $title, 'cf7bs_form_label' );
								}
								$output .= '<div class="radio">';
								$output .= '<label' . ( ! empty( $id ) ? ' for="' . esc_attr( $id . ( $counter + 1 ) ) . '"' : '' ) . '>';
								$output .= '<input' . $input_class . ( ! empty( $id ) ? ' id="' . esc_attr( $id . ( $counter + 1 ) ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" type="radio" value="' . esc_attr( $curval ) . '"' . cf7bs_checked( $value, $curval, false ) . ( is_int( $tabindex ) ? ' tabindex="' . ( $tabindex + $counter ) . '"' : '' ) . $append . '>';
								$output .= $title;
								$output .= '</label>';
								$output .= '</div>';
								$counter++;
							}
						}
						if ( defined( 'CF7BS_FIELDSET_WRAP' ) && CF7BS_FIELDSET_WRAP ) {
							$output .= '</fieldset>';
						}
					}
					break;
				case 'textarea':
					if ( ! empty( $input_before ) && 'inline' != $form_layout ) {
						$input_before_class = trim( str_replace( 'input-group-addon', '', $input_before_class ) );
						$output .= '<p class="text-right' . ( ! empty( $input_before_class ) ? ' ' . $input_before_class : '' ) . '">' . $input_before . '</p>';
					}
					$output .= '<textarea' . $input_class . ( ! empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" rows="' . absint( $rows ) . '"' . $placeholder . $readonly . $minlength . $maxlength . $tabindex . $append . '>';
					$output .= esc_textarea( $value );
					$output .= '</textarea>';
					if ( ! empty( $input_after ) && 'inline' != $form_layout ) {
						$input_after_class = trim( str_replace( 'input-group-addon', '', $input_after_class ) );
						$output .= '<p class="text-right' . ( ! empty( $input_after_class ) ? ' ' . $input_after_class : '' ) . '">' . $input_after . '</p>';
					}
					break;
				case 'file':
					$output .= '<input' . $input_class . ( ! empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" type="file"' . $tabindex . $append . '>';
					break;
				case 'hidden':
					$output .= '<input' . ( ! empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" type="hidden" value="' . esc_attr( $value ) . '">';
					break;
				case 'number':
					$input_class = $this->filter_input_class( $input_class, $input_before, $input_after );

					$min = '';
					if ( isset( $options['min'] ) ) {
						$min = ' min="' . esc_attr( $options['min'] ) . '"';
					}
					$max = '';
					if ( isset( $options['max'] ) ) {
						$max = ' max="' . esc_attr( $options['max'] ) . '"';
					}
					$step = '';
					if ( isset( $options['step'] ) ) {
						$step = ' step="' . esc_attr( $options['step'] ) . '"';
					}

					$output .= $this->get_input_before_markup( $input_before, $input_after, $input_before_class, $input_class );
					$output .= '<input' . $input_class . ( ! empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" type="' . esc_attr( $type ) . '" value="' . esc_attr( $value ) . '"' . $placeholder . $min . $max . $step . $readonly . $tabindex . $append . '>';
					$output .= $this->get_input_after_markup( $input_before, $input_after, $input_after_class );
					break;
				case 'range':
				case 'date':
				case 'datetime':
				case 'datetime-local':
				case 'month':
				case 'time':
				case 'week':
					$min = '';
					if ( isset( $options['min'] ) ) {
						$min = ' min="' . esc_attr( $options['min'] ) . '"';
					}
					$max = '';
					if ( isset( $options['max'] ) ) {
						$max = ' max="' . esc_attr( $options['max'] ) . '"';
					}
					$step = '';
					if ( isset( $options['step'] ) ) {
						$step = ' step="' . esc_attr( $options['step'] ) . '"';
					}
					$output .= '<input' . $input_class . ( ! empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" type="' . esc_attr( $type ) . '" value="' . esc_attr( $value ) . '"' . $placeholder . $min . $max . $step . $readonly . $tabindex . $append . '>';
					break;
				case 'custom':
					if ( ! empty( $name ) ) {
						$output .= $name;
					}
					break;
				default:
					if ( 'static' == $mode ) {
						$output .= '<p class="form-control-static">' . esc_html( $value ) . '</p>';
					} else {
						$input_class = $this->filter_input_class( $input_class, $input_before, $input_after );

						$output .= $this->get_input_before_markup( $input_before, $input_after, $input_before_class, $input_class );
						$output .= '<input' . $input_class . ( ! empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" type="' . esc_attr( $type ) . '" value="' . esc_attr( $value ) . '"' . $placeholder . $readonly . $minlength . $maxlength . $tabindex . $append . '>';
						$output .= $this->get_input_after_markup( $input_before, $input_after, $input_after_class );
					}
					break;
			}

			if ( 'hidden' != $type && 'none' != $form_layout ) {
				if ( ! empty( $help_text ) && 'inline' != $form_layout ) {
					$output .= '<span class="help-block">' . $help_text . '</span>';
				}

				if ( 'horizontal' == $form_layout ) {
					$output .= '</div>';
					$output .= '</div>';
				} else {
					$output .= '</div>';
				}
			}
		}

		if ( $echo ) {
			echo $output;
		}
		return $output;
	}

	protected function filter_input_class( $input_class, $input_before, $input_after ) {
		if ( empty( $input_before ) && empty( $input_after ) ) {
			return $input_class;
		}

		if ( false !== strpos( $input_class, ' input-lg' ) ) {
			$input_class = str_replace( ' input-lg', '', $input_class );
		} elseif ( false !== strpos( $input_class, ' input-sm' ) ) {
			$input_class = str_replace( ' input-sm', '', $input_class );
		}

		return $input_class;
	}

	protected function get_input_before_markup( $input_before, $input_after, $input_before_class, $input_class ) {
		if ( empty( $input_before ) && empty( $input_after ) ) {
			return '';
		}

		$input_group_class = 'input-group';
		if ( false !== strpos( $input_class, ' input-lg') ) {
			$input_group_class .= ' input-group-lg';
		} elseif ( false !== strpos( $input_class, ' input-sm') ) {
			$input_group_class .= ' input-group-sm';
		}

		$markup = '<div class="' . $input_group_class . '">';
		if ( ! empty( $input_before ) ) {
			$markup .= '<span class="' . esc_attr( $input_before_class ) . '">';
			$markup .= $input_before;
			$markup .= '</span>';
		}

		return $markup;
	}

	protected function get_input_after_markup( $input_before, $input_after, $input_after_class ) {
		if ( empty( $input_before ) && empty( $input_after ) ) {
			return '';
		}

		$markup = '';
		if ( ! empty( $input_after ) ) {
			$markup .= '<span class="' . esc_attr( $input_after_class ) . '">';
			$markup .= $input_after;
			$markup .= '</span>';
		}
		$markup .= '</div>';

		return $markup;
	}

	protected function validate_args( $args, $exclude = array() ) {
		$exclude[] = 'value';
		$exclude[] = 'maxlength';
		$exclude[] = 'tabindex';
		$args = parent::validate_args( $args, $exclude );

		// type whitelist check is made later in the display() function to allow different types to use in a filter

		return $args;
	}

	protected function get_defaults() {
		$defaults = array(
			'name'					=> '',
			'id'					=> '',
			'class'					=> '',
			'type'					=> 'text',
			'value'					=> '', // for multiselect and multiple checkbox an array, for singular checkboxes and all others a string
			'placeholder'			=> '',
			'label'					=> '',
			'options'				=> array(), // for select, multiselect, checkbox and radio: value => title; for number, range and all datetime-related fields: min, max, step
			'rows'					=> 4,
			'help_text'				=> '',
			'size'					=> 'default', // default, large, small, mini
			'grid_columns'			=> 12,
			'form_layout'			=> 'default', // default, inline, horizontal, none
			'form_label_width'		=> 2,
			'form_breakpoint'		=> 'sm',
			'mode'					=> 'default', // default, required, static, disabled
			'status'				=> 'default', // default, success, warning, error
			'readonly'				=> false,
			'minlength'				=> false,
			'maxlength'				=> false,
			'tabindex'				=> false,
			'group_layout'			=> 'default', // default, inline, buttons
			'group_type'			=> 'default', // only if group_layout==buttons
			'wrapper_class'			=> '',
			'label_class'           => '',
			'input_before'			=> '',
			'input_after'			=> '',
			'input_before_class'	=> 'input-group-addon',
			'input_after_class'		=> 'input-group-addon',
		);
		return apply_filters( 'cf7bs_bootstrap_form_field_defaults', $defaults );
	}

	private function validate_type( $type ) {
		$whitelist = array(
			'text',
			'password',
			'datetime',
			'datetime-local',
			'date',
			'month',
			'time',
			'week',
			'number',
			'range',
			'email',
			'url',
			'search',
			'tel',
			'color',
			'textarea',
			'file',
			'hidden',
			'select',
			'multiselect',
			'checkbox',
			'radio',
			'custom',
		);

		$type = strtolower( $type );
		if ( ! in_array( $type, $whitelist ) ) {
			$type = 'text';
		}
		return $type;
	}

	private function validate_value( $value, $type, $options = array() ) {
		if ( 'multiselect' == $type || 'checkbox' == $type && is_array( $options ) && count( $options ) > 1 ) {
			$value = (array) $value;
		} else {
			if ( is_array( $value ) ) {
				if ( count( $value ) > 0 ) {
					reset( $value );
					$value = $value[ key( $value ) ];
				} else {
					$value = '';
				}
			}
			$value = (string) $value;
		}
		return $value;
	}

	private function get_column_width_classes( $label_column_width = 2, $breakpoint = 'sm', $grid_columns = 12 ) {
		if ( $label_column_width > $grid_columns - 1 || $label_column_width < 1 ) {
			$label_column_width = 2;
		}
		if ( ! in_array( $breakpoint, array( 'xs', 'sm', 'md', 'lg' ) ) ) {
			$breakpoint = 'sm';
		}
		return array(
			'label'		=> 'col-' . $breakpoint . '-' . $label_column_width,
			'input'		=> 'col-' . $breakpoint . '-' . ( $grid_columns - $label_column_width ),
		);
	}

	private function get_column_offset_class( $label_column_width = 2, $breakpoint = 'sm', $grid_columns = 12 ) {
		if ( $label_column_width > $grid_columns - 1 || $label_column_width < 1 ) {
			$label_column_width = 2;
		}
		if ( ! in_array( $breakpoint, array( 'xs', 'sm', 'md', 'lg' ) ) ) {
			$breakpoint = 'sm';
		}
		return 'col-' . $breakpoint . '-offset-' . $label_column_width;
	}
}
