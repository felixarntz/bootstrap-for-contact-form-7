<?php
/**
 * @package CF7BS
 * @version 1.1.0
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

class CF7BS_Alert extends CF7BS_Component
{
  public function open( $echo = true )
  {
    $output = apply_filters( 'cf7bs_bootstrap_alert_open', '', $this->args );
    
    if( empty( $output ) )
    {
      $output = '';
      
      extract( $this->args );
      
      $type = $this->validate_type( $type );
      
      if( !empty( $class ) )
      {
        $class .= ' ';
      }
      $class .= 'alert';
      if( !empty( $type ) )
      {
        $class .= ' alert-' . $type;
      }
      if( $dismissable )
      {
        $class .= ' alert-dismissable';
      }
      
      $output .= '<div class="' . esc_attr( $class ) . '"' . ( $hide ? ' style="display:none;"' : '' ) . '>';
      if( $dismissable )
      {
        $output .= '<button class="close" data-dismiss="alert" type="button">&times;</button>';
      }
    }
    
    if( $echo )
    {
      echo $output;
    }
    return $output;
  }
  
  public function close( $echo = true )
  {
    $output = apply_filters( 'cf7bs_bootstrap_alert_close', '', $this->args );
    
    if( empty( $output ) )
    {
      $output .= '</div>';
    }
    
    if( $echo )
    {
      echo $output;
    }
    return $output;
  }
  
  protected function validate_args( $args, $exclude = array() )
  {
    $args = parent::validate_args( $args, $exclude );
    
    return $args;
  }
  
  protected function get_defaults()
  {
    $defaults = array(
      'type'        => 'default',
      'class'       => '',
      'dismissable' => false,
      'hide'        => false,
    );
    return apply_filters( 'cf7bs_bootstrap_alert_defaults', $defaults );
  }
  
  private function validate_type( $type )
  {
    $whitelist = array(
      'success',
      'info',
      'warning',
      'danger'
    );
    
    if( !in_array( $type, $whitelist ) )
    {
      $type = '';
    }
    return $type;
  }
}
