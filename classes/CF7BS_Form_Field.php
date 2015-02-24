<?php
/**
 * @package CF7BS
 * @version 1.1.0
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

class CF7BS_Form_Field extends CF7BS_Component
{
  public function display( $echo = true )
  {
    $output = apply_filters( 'cf7bs_bootstrap_form_field_display', '', $this->args );
    
    if( empty( $output ) )
    {
      $output = '';

      extract( $this->args );
      
      $type = $this->validate_type( $type );

      $value = $this->validate_value( $value, $type, $options );
      
      if( $type != 'hidden' )
      {
        $label_class = 'control-label';
        $input_div_class = '';
        $input_class = $class;
        if( $form_layout == 'horizontal' )
        {
          $classes = $this->get_column_width_classes( $form_label_width, $form_breakpoint );
          $label_class .= ' ' . $classes['label'];
          $input_div_class = $classes['input'];
          if( empty( $label ) || in_array( $type, array( 'radio', 'checkbox' ) ) && count( $options ) <= 1 )
          {
            $input_div_class .= ' ' . $this->get_column_offset_class( $form_label_width, $form_breakpoint );
          }
        }
        elseif( $form_layout == 'inline' )
        {
          if( empty( $placeholder ) )
          {
            $placeholder = $label;
          }
        }

        if( !empty( $wrapper_class ) )
        {
          $wrapper_class = ' ' . esc_attr( $wrapper_class );
        }
        
        if( !in_array( $type, array( 'radio', 'checkbox' ) ) )
        {
          if( !empty( $input_class ) )
          {
            $input_class .= ' ';
          }
          if( !in_array( $type, array( 'file', 'range' ) ) )
          {
            $input_class .= 'form-control';
          }
          
          if( $type != 'textarea' )
          {
            if( $size == 'large' )
            {
              $input_class .= ' input-lg';
            }
            elseif( $size == 'small' || $size == 'mini' )
            {
              $input_class .= ' input-sm';
            }
          }

          if( is_int( $tabindex ) )
          {
            $tabindex = ' tabindex="' . $tabindex . '"';
          }
          else
          {
            $tabindex = '';
          }
        }
        
        if( !empty( $input_class ) )
        {
          $input_class = ' class="' . esc_attr( $input_class ) . '"';
        }
        if( !empty( $placeholder ) )
        {
          $placeholder = ' placeholder="' . esc_attr( $placeholder ) . '"';
        }

        if( $readonly )
        {
          $readonly = ' readonly';
        }
        else
        {
          $readonly = '';
        }
        if( $maxlength > -1 && !empty( $maxlength ) )
        {
          $maxlength = ' maxlength="' . absint( $maxlength ) . '"';
        }
        else
        {
          $maxlength = '';
        }
        
        $append = '';
        if( $mode == 'required' )
        {
          $append = ' required';
        }
        elseif( $mode == 'disabled' )
        {
          $append = ' disabled';
        }
        
        $label_required = '';
        if( $mode == 'required' )
        {
          $label_required = ' ' . cf7bs_get_form_property( 'required_html' );
        }

        if( in_array( $status, array( 'success', 'warning', 'error' ) ) )
        {
          $status = ' has-' . $status;
        }
        else
        {
          $status = '';
        }
        
        if( $form_layout == 'horizontal' )
        {
          $output .= '<div class="form-group' . $wrapper_class . $status . '">';
          if( !empty( $label ) && ( !in_array( $type, array( 'radio', 'checkbox' ) ) || count( $options ) > 1 ) )
          {
            $output .= '<label class="' . esc_attr( $label_class ) . '" for="' . esc_attr( $id ) . '">' . esc_html( $label ) . $label_required . '</label>';
          }
          $output .= '<div class="' . esc_attr( $input_div_class ) . '">';
        }
        elseif( $form_layout == 'inline' )
        {
          if( !in_array( $type, array( 'radio', 'checkbox' ) ) || count( $options ) > 1 )
          {
            $output .= '<div class="form-group' . $wrapper_class . $status . '">';
            if( !empty( $label ) )
            {
              $output .= '<label class="sr-only" for="' . esc_attr( $id ) . '">' . esc_html( $label ) . $label_required . '</label>';
            }
          }
        }
        else
        {
          if( !in_array( $type, array( 'radio', 'checkbox' ) ) || count( $options ) > 1 )
          {
            $output .= '<div class="form-group' . $wrapper_class . $status . '">';
            if( !empty( $label ) )
            {
              $rc_group_style = '';
              if( in_array( $type, array( 'radio', 'checkbox' ) ) )
              {
                $rc_group_style = ' style="display:block;"';
              }
              $output .= '<label for="' . esc_attr( $id ) . '"' . $rc_group_style . '>' . esc_html( $label ) . $label_required . '</label>';
            }
          }
        }
      }
      
      switch( $type )
      {
        case 'checkbox':
          if( count( $options ) <= 1 )
          {
            if( count( $options ) < 1 )
            {
              $curval = 'true';
              $title = $label;
            }
            else
            {
              reset( $options );
              $curval = key( $options );
              $title = $options[ $curval ];
            }
            $output .= '<div class="checkbox">';
            $output .= '<label>';
            $output .= '<input' . $input_class . ( !empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" type="checkbox" value="' . esc_attr( $curval ) . '"' . cf7bs_checked( $value, $curval, false ) . ( is_int( $tabindex ) ? ' tabindex="' . $tabindex . '"' : '' ) . $append . '>';
            $output .= esc_html( $title );
            $output .= '</label>';
            $output .= '</div>';
          }
          else
          {
            if( $group_layout == 'buttons' )
            {
              $button_group = new CF7BS_Button_Group( array(
                'mode'    => 'checkbox',
                'size'    => $size,
              ) );
              $output .= $button_group->open( false );
              $counter = 0;
              foreach( $options as $curval => $title )
              {
                $is_checked = cf7bs_multiple_checked( $value, $curval, false );
                $output .= $button_group->insert_button( array(
                  'type'    => $group_type,
                  'id'      => $id . ( $counter + 1 ),
                  'name'    => $name . '[]',
                  'class'   => $class,
                  'value'   => $curval,
                  'title'   => $title,
                  'append'  => ( is_int( $tabindex ) ? ' tabindex="' . ( $tabindex + $counter ) . '"' : '' ) . $is_checked . $append,
                ), false );
                $counter++;
              }
              $output .= $button_group->close( false );
            }
            elseif( $group_layout == 'inline' && $form_layout != 'inline' )
            {
              $counter = 0;
              foreach( $options as $curval => $title )
              {
                $output .= '<label class="checkbox-inline">';
                $output .= '<input' . $input_class . ( !empty( $id ) ? ' id="' . esc_attr( $id . ( $counter + 1 ) ) . '"' : '' ) . ' name="' . esc_attr( $name . '[]' ) . '" type="checkbox" value="' . esc_attr( $curval ) . '"' . cf7bs_multiple_checked( $value, $curval, false ) . ( $tabindex >= 0 ? ' tabindex="' . ( $tabindex + $counter ) . '"' : '' ) . $append . '>';
                $output .= esc_html( $title );
                $output .= '</label>';
                $counter++;
              }
            }
            else
            {
              $counter = 0;
              foreach( $options as $curval => $title )
              {
                $output .= '<div class="checkbox">';
                $output .= '<label>';
                $output .= '<input' . $input_class . ( !empty( $id ) ? ' id="' . esc_attr( $id . ( $counter + 1 ) ) . '"' : '' ) . ' name="' . esc_attr( $name . '[]' ) . '" type="checkbox" value="' . esc_attr( $curval ) . '"' . cf7bs_multiple_checked( $value, $curval, false ) . ( is_int( $tabindex ) ? ' tabindex="' . ( $tabindex + $counter ) . '"' : '' ) . $append . '>';
                $output .= esc_html( $title );
                $output .= '</label>';
                $output .= '</div>';
                $counter++;
              }
            }
          }
          break;
        case 'select':
          $output .= '<select' . $input_class . ( !empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '"' . $tabindex . $append . '>';
          foreach( $options as $curval => $title )
          {
            $output .= '<option value="' . esc_attr( $curval ) . '"' . cf7bs_selected( $value, $curval, false ) . '>' . esc_html( $title ) . '</option>';
          }
          $output .= '</select>';
          break;
        case 'multiselect':
          $output .= '<select' . $input_class . ( !empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' name="' . esc_attr( $name . '[]' ) . '" multiple' . $tabindex . $append . '>';
          foreach( $options as $curval => $title )
          {
            $output .= '<option value="' . esc_attr( $curval ) . '"' . cf7bs_multiple_selected( $value, $curval, false ) . '>' . esc_html( $title ) . '</option>';
          }
          $output .= '</select>';
          break;
        case 'radio':
          if( count( $options ) <= 1 )
          {
            if( count( $options ) < 1 )
            {
              $curval = 'true';
              $title = $label;
            }
            else
            {
              reset( $options );
              $curval = key( $options );
              $title = $options[ $curval ];
            }
            $output .= '<div class="radio">';
            $output .= '<label>';
            $output .= '<input' . $input_class . ( !empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" type="radio" value="' . esc_attr( $curval ) . '"' . cf7bs_checked( $value, $curval, false ) . ( is_int( $tabindex ) ? ' tabindex="' . $tabindex . '"' : '' ) . $append . '>'; 
            $output .= esc_html( $title );
            $output .= '</label>';
            $output .= '</div>';
          }
          else
          {
            if( $group_layout == 'buttons' )
            {
              $button_group = new CF7BS_Button_Group( array(
                'mode'    => 'radio',
                'size'    => $size,
              ) );
              $output .= $button_group->open( false );
              $counter = 0;
              foreach( $options as $curval => $title )
              {
                $is_checked = cf7bs_checked( $value, $curval, false );
                $output .= $button_group->insert_button( array(
                  'type'    => $group_type,
                  'id'      => $id . ( $counter + 1 ),
                  'name'    => $name,
                  'class'   => $class,
                  'value'   => $curval,
                  'title'   => $title,
                  'append'  => ( is_int( $tabindex ) ? ' tabindex="' . ( $tabindex + $counter ) . '"' : '' ) . $is_checked . $append,
                ), false );
                $counter++;
              }
              $output .= $button_group->close( false );
            }
            elseif( $group_layout == 'inline' && $form_layout != 'inline' )
            {
              $counter = 0;
              foreach( $options as $curval => $title )
              {
                $output .= '<label class="radio-inline">';
                $output .= '<input' . $input_class . ( !empty( $id ) ? ' id="' . esc_attr( $id . ( $counter + 1 ) ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" type="radio" value="' . esc_attr( $curval ) . '"' . cf7bs_checked( $value, $curval, false ) . ( $tabindex >= 0 ? ' tabindex="' . ( $tabindex + $counter ) . '"' : '' ) . $append . '>';
                $output .= esc_html( $title );
                $output .= '</label>';
                $counter++;
              }
            }
            else
            {
              $counter = 0;
              foreach( $options as $curval => $title )
              {
                $output .= '<div class="radio">';
                $output .= '<label>';
                $output .= '<input' . $input_class . ( !empty( $id ) ? ' id="' . esc_attr( $id . ( $counter + 1 ) ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" type="radio" value="' . esc_attr( $curval ) . '"' . cf7bs_checked( $value, $curval, false ) . ( is_int( $tabindex ) ? ' tabindex="' . ( $tabindex + $counter ) . '"' : '' ) . $append . '>';
                $output .= esc_html( $title );
                $output .= '</label>';
                $output .= '</div>';
                $counter++;
              }
            }
          }
          break;
        case 'textarea':
          $output .= '<textarea' . $input_class . ( !empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" rows="' . absint( $rows ) . '"' . $placeholder . $readonly . $tabindex . $append . '>';
          $output .= esc_textarea( $value );
          $output .= '</textarea>';
          break;
        case 'file':
          $output .= '<input' . $input_class . ( !empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" type="file"' . $tabindex . $append . '>';
          break;
        case 'hidden':
          $output .= '<input' . ( !empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" type="hidden" value="' . esc_attr( $value ) . '">';
          break;
        case 'number':
        case 'range':
        case 'date':
        case 'datetime':
        case 'datetime-local':
        case 'month':
        case 'time':
        case 'week':
          $min = '';
          if( isset( $options['min'] ) )
          {
            $min = ' min="' . esc_attr( $options['min'] ) . '"';
          }
          $max = '';
          if( isset( $options['max'] ) )
          {
            $max = ' max="' . esc_attr( $options['max'] ) . '"';
          }
          $step = '';
          if( isset( $options['step'] ) )
          {
            $step = ' step="' . esc_attr( $options['step'] ) . '"';
          }
          $output .= '<input' . $input_class . ( !empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" type="' . esc_attr( $type ) . '" value="' . esc_attr( $value ) . '"' . $placeholder . $min . $max . $step . $readonly . $tabindex . $append . '>';
          break;
        default:
          if( $mode == 'static' )
          {
            $output .= '<p class="form-control-static">' . esc_html( $value ) . '</p>';
          }
          else
          {
            if( !empty( $input_before ) || !empty( $input_after ) )
            {
              $input_group_class = 'input-group';
              if( strpos( $input_class, ' input-lg') !== false )
              {
                $input_class = str_replace( ' input-lg', '', $input_class );
                $input_group_class .= ' input-group-lg';
              }
              elseif( strpos( $input_class, ' input-sm') !== false )
              {
                $input_class = str_replace( ' input-sm', '', $input_class );
                $input_group_class .= ' input-group-sm';
              }
              $output .= '<div class="' . $input_group_class . '">';
              if( !empty( $input_before ) )
              {
                $output .= '<span class="' . esc_attr( $input_before_class ) . '">';
                $output .= $input_before;
                $output .= '</span>';
              }
            }

            $output .= '<input' . $input_class . ( !empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" type="' . esc_attr( $type ) . '" value="' . esc_attr( $value ) . '"' . $placeholder . $readonly . $maxlength . $tabindex . $append . '>';
            
            if( !empty( $input_before ) || !empty( $input_after ) )
            {
              if( !empty( $input_after ) )
              {
                $output .= '<span class="' . esc_attr( $input_after_class ) . '">';
                $output .= $input_after;
                $output .= '</span>';
              }
              $output .= '</div>';
            }
          }
          break;
      }
      
      if( $type != 'hidden' )
      {
        if( !empty( $help_text ) && $form_layout != 'inline' )
        {
          $output .= '<span class="help-block">' . $help_text . '</span>';
        }
        
        if( $form_layout == 'horizontal' )
        {
          $output .= '</div>';
          $output .= '</div>';
        }
        else
        {
          if( !in_array( $type, array( 'radio', 'checkbox' ) ) || count( $options ) > 1 )
          {
            $output .= '</div>';
          }
        }
      }
    }
    
    if( $echo )
    {
      echo $output;
    }
    return $output;
  }
  
  protected function validate_args( $args, $exclude = array() )
  {
    $exclude[] = 'value';
    $exclude[] = 'maxlength';
    $exclude[] = 'tabindex';
    $args = parent::validate_args( $args, $exclude );
    
    // type whitelist check is made later in the display() function to allow different types to use in a filter
    
    return $args;
  }
  
  protected function get_defaults()
  {
    $defaults = array(
      'name'                => '',
      'id'                  => '',
      'class'               => '',
      'type'                => 'text',
      'value'               => '', // for multiselect and multiple checkbox an array, for singular checkboxes and all others a string
      'placeholder'         => '',
      'label'               => '',
      'options'             => array(), // for select, multiselect, checkbox and radio: value => title; for number, range and all datetime-related fields: min, max, step
      'rows'                => 4,
      'help_text'           => '',
      'size'                => 'default', // default, large, small, mini
      'form_layout'         => 'default', // default, inline, horizontal
      'form_label_width'    => 2,
      'form_breakpoint'     => 'sm',
      'mode'                => 'default', // default, required, static, disabled
      'status'              => 'default', // default, success, warning, error
      'readonly'            => false,
      'maxlength'           => false,
      'tabindex'            => false,
      'group_layout'        => 'default', // default, inline, buttons
      'group_type'          => 'default', // only if group_layout==buttons
      'wrapper_class'       => '',
      'input_before'        => '',
      'input_after'         => '',
      'input_before_class'  => 'input-group-addon',
      'input_after_class'   => 'input-group-addon',
    );
    return apply_filters( 'cf7bs_bootstrap_form_field_defaults', $defaults );
  }
  
  private function validate_type( $type )
  {
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
    );
    
    $type = strtolower( $type );
    if( !in_array( $type, $whitelist ) )
    {
      $type = 'text';
    }
    return $type;
  }

  private function validate_value( $value, $type, $options = array() )
  {
    if( $type == 'multiselect' || $type == 'checkbox' && is_array( $options ) && count( $options ) > 1 )
    {
      $value = (array) $value;
    }
    else
    {
      $value = (string) $value;
    }
    return $value;
  }
  
  private function get_column_width_classes( $label_column_width = 2, $breakpoint = 'sm' )
  {
    if( $label_column_width > 11 || $label_column_width < 1 )
    {
      $label_column_width = 2;
    }
    if( !in_array( $breakpoint, array( 'xs', 'sm', 'md', 'lg' ) ) )
    {
      $breakpoint = 'sm';
    }
    return array(
      'label'   => 'col-' . $breakpoint . '-' . $label_column_width,
      'input'   => 'col-' . $breakpoint . '-' . ( 12 - $label_column_width ),
    );
  }
  
  private function get_column_offset_class( $label_column_width = 2, $breakpoint = 'sm' )
  {
    if( $label_column_width > 11 || $label_column_width < 1 )
    {
      $label_column_width = 2;
    }
    if( !in_array( $breakpoint, array( 'xs', 'sm', 'md', 'lg' ) ) )
    {
      $breakpoint = 'sm';
    }
    return 'col-' . $breakpoint . '-offset-' . $label_column_width;
  }
}
