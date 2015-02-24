<?php
/**
 * @package CF7BS
 * @version 1.1.0
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

class CF7BS_Button extends CF7BS_Component
{
  public function display( $echo = true )
  {
    $output = apply_filters( 'cf7bs_bootstrap_button_display', '', $this->args );
    
    if( empty( $output ) )
    {
      $output = '';
      
      extract( $this->args );
      
      $type = $this->validate_type( $type );
      
      if( !empty( $class ) )
      {
        $class .= ' ';
      }
      
      $class .= 'btn btn-' . $type;
      
      $sizes = array(
        'mini'    => 'xs',
        'small'   => 'sm',
        'large'   => 'lg',
      );
      if( isset( $sizes[ $size ] ) )
      {
        $class .= ' btn-' . $sizes[ $size ];
      }
      
      if( !empty( $id ) )
      {
        $id = ' id="' . esc_attr( $id ) . '"';
      }
      
      if( !empty( $name ) )
      {
        $name = ' name="' . esc_attr( $name ) . '"';
      }
      
      if( $mode == 'checkbox' )
      {
        $output .= '<label class="' . esc_attr( $class ) . '"><input' . $id . $name . ' type="checkbox" value="' . esc_attr( $value ) . '"' . $append . '>' . esc_html( $title ) . '</label>';
      }
      elseif( $mode == 'radio' )
      {
        $output .= '<label class="' . esc_attr( $class ) . '"><input' . $id . $name . ' type="radio" value="' . esc_attr( $value ) . '"' . $append . '>' . esc_html( $title ) . '</label>';
      }
      else
      {
        $wrapper_class = array();

        if( $align && $form_layout != 'inline' )
        {
          $wrapper_class[] = 'text-' . $align;
        }

        if( $form_layout == 'horizontal' )
        {
          $wrapper_class[] = $this->get_column_class( $form_label_width, $form_breakpoint );
        }

        $wrapper_class = implode( ' ', $wrapper_class );

        if( !empty( $wrapper_class ) )
        {
          $wrapper_class = ' class="' . $wrapper_class . '"';
        }

        if( is_int( $tabindex ) )
        {
          $tabindex = ' tabindex="' . $tabindex . '"';
        }
        else
        {
          $tabindex = '';
        }
        $output .= '<div class="form-group"><div' . $wrapper_class . '>';
        $output .= '<input class="' . esc_attr( $class ) . '"' . $id . $name . ' type="submit" value="' . esc_attr( $title ) . '"' . $tabindex . '>';
        $output .= '</div></div>';
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
    $exclude[] = 'tabindex';
    $exclude[] = 'align';
    $args = parent::validate_args( $args, $exclude );

    if( is_string( $args['align'] ) )
    {
      $args['align'] = strtolower( $args['align'] );
    }

    if( !in_array( $args['align'], array( 'left', 'center', 'right' ) ) )
    {
      $args['align'] = false;
    }
    
    // type whitelist check is made later in the display() function to allow different types to use in a filter
    
    return $args;
  }
  
  protected function get_defaults()
  {
    $defaults = array(
      'type'                => 'default',
      'size'                => 'default', // default, large, small, mini
      'mode'                => 'submit', // checkbox, radio, submit
      'id'                  => '',
      'class'               => '',
      'title'               => 'Button Title',
      'name'                => '',
      'append'              => '', // for checkbox/radio only
      'value'               => '', // for checkbox/radio only
      'tabindex'            => false,
      'align'               => false,
      'form_layout'         => 'default', // default, inline, horizontal
      'form_label_width'    => 2,
      'form_breakpoint'     => 'sm',
    );
    return apply_filters( 'cf7bs_bootstrap_button_defaults', $defaults );
  }
  
  private function validate_type( $type )
  {
    $whitelist = array(
      'default',
      'primary',
      'info',
      'success',
      'warning',
      'danger',
      'link'
    );
    
    $type = strtolower( $type );
    if( !in_array( $type, $whitelist ) )
    {
      $type = 'default';
    }
    return $type;
  }

  private function get_column_class( $label_column_width = 2, $breakpoint = 'sm' )
  {
    if( $label_column_width > 11 || $label_column_width < 1 )
    {
      $label_column_width = 2;
    }
    if( !in_array( $breakpoint, array( 'xs', 'sm', 'md', 'lg' ) ) )
    {
      $breakpoint = 'sm';
    }
    return 'col-' . $breakpoint . '-' . ( 12 - $label_column_width ) . ' col-' . $breakpoint . '-offset-' . $label_column_width;
  }
}
