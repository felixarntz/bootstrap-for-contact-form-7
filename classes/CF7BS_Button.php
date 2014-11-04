<?php
/**
 * @package CF7BS
 * @version 1.0.0
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
        if( is_int( $tabindex ) )
        {
          $tabindex = ' tabindex="' . $tabindex . '"';
        }
        else
        {
          $tabindex = '';
        }
        $output .= '<input class="' . esc_attr( $class ) . '"' . $id . $name . ' type="submit" value="' . esc_attr( $title ) . '"' . $tabindex . '>';
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
    $args = parent::validate_args( $args, $exclude );
    
    // type whitelist check is made later in the display() function to allow different types to use in a filter
    
    return $args;
  }
  
  protected function get_defaults()
  {
    $defaults = array(
      'type'      => 'default',
      'size'      => 'default', // default, large, small, mini
      'mode'      => 'submit', // checkbox, radio, submit
      'id'        => '',
      'class'     => '',
      'title'     => 'Button Title',
      'name'      => '',
      'append'    => '', // for checkbox/radio only
      'value'     => '', // for checkbox/radio only
      'tabindex'  => false,
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
}
