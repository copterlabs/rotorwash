<?php
/**
 * A base class for rapid widget creation
 *
 * @package     WordPress
 * @subpackage  RotorWash
 * @since       1.0.5
 */

/**
 * A simple class to allow for rapid widget development
 * 
 * @category   Widgets
 * @package    WordPress
 * @author     Jason Lengstorf <jason.lengstorf@copterlabs.com>
 * @copyright  2012 Copter Labs
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    1.0
 * @link       http://github.com/copterlabs/Quick_Widget
 * @see        WP_Widget
 */
class Quick_Widget extends WP_Widget
{
    /**
     * Widget settings
     * 
        array(
                'name'      => 'Title',
                'desc'      => '',
                'id'        => 'title',
                'type'      => 'text',
                'default'   => 'Your widgets title',
            ),
        array(
                'name'      => 'Textarea',
                'desc'      => 'Enter big text here',
                'id'        => 'textarea_id',
                'type'      => 'textarea',
                'default'   => 'Default value 2',
            ),
        array(
                'name'      => 'Select box',
                'desc'      => '',
                'id'        => 'select_id',
                'type'      => 'select',
                'options'   => array( 'KEY1' => 'Value 1', 'KEY2' => 'Value 2', 'KEY3' => 'Value 3' ),
            ),
        array(
                'name'      => 'Radio',
                'desc'      => '',
                'id'        => 'radio_id',
                'type'      => 'radio',
                'options'   => array(
                                    array('name' => 'Name 1', 'value' => 'Value 1'),
                                    array('name' => 'Name 2', 'value' => 'Value 2'),
                                )
            ),
        array(
                'name' => 'Checkbox',
                'desc' => '',
                'id' => 'checkbox_id',
                'type' => 'checkbox',
            ),
     */
    protected $settings = array(
            // Set up a unique ID for the widget
            'widget_id' => '',

            // Give the widget a name for display in the Widgets panel
            'name' => '',

            // this description will display within the administrative widgets area
            // when a user is deciding which widget to use.
            'description' => '',
            
            // determines whether or not to use the sidebar _before and _after html
            'do_wrapper' => TRUE, 

            'view_callback' => FALSE,

            'fields' => array(
                    // You should always offer a widget title
                    array(
                            'name'      => 'Title',
                            'desc'      => 'The widget title',
                            'id'        => 'title',
                            'type'      => 'text',
                            'default'   => 'Widget Title'
                        ),
                ),
        );
    
    /**
     * Registers the widget
     * 
     * @return void
     */
    public function __construct(  )
    {
        // widget_id is required for the widget to function
        if( empty($this->settings['widget_id']) )
        {
            trigger_error('Quick_Widget Error: You must provide a widget_id.', E_USER_ERROR);
            return;
        }
        else
        {
            $id = preg_replace('/[^\w-]/', '', $this->settings['widget_id']);
        }

        // Check for the name of the widget
        if( empty($this->settings['name']) )
        {
            $name = 'My Great Widget';
            trigger_error('Quick_Widget Error: You should set a name for your widget.');
        }
        else
        {
            $name = preg_replace('/[^\w- ]/', '', $this->settings['name']);
        }

        // Widget options
        $options = array(
                'description' => $this->settings['description'],
            );

        parent::__construct($id, $name, $options);
    }
    
    /**
     * Widget HTML
     * 
     * If you want to have an all inclusive single widget file, you can do so by
     * dumping your css styles with base_encoded images along with all of your 
     * html string, right into this method.
     *
     * @param array $widget
     * @param array $params
     * @param array $sidebar
     */
    protected function html($widget, $params, $sidebar)
    {
        extract($params);

        if( !empty($title) )
        {
            echo $sidebar['before_title'] . $title . $sidebar['after_title'];
        }

        foreach( $params as $name => $param ):

?>
                    <p><?php echo '<strong>', $name, ':</strong> ', $param; ?></p>

<?php

        endforeach;
    }
    
    /**
     * Widget View
     * 
     * This method determines what view method is being used and gives that view
     * method the proper parameters to operate. This method does not need to be
     * changed.
     *
     * @param array $sidebar
     * @param array $params
     * @return void
     */
    function widget( $sidebar, $params )
    {
        $this->settings['number'] = $this->number;

        $title = apply_filters(__CLASS__.'_title', $params['title']);

        $do_wrapper = !isset($this->settings['do_wrapper']) || $this->settings['do_wrapper'];
        
        echo $do_wrapper ? $sidebar['before_widget'] : NULL;

        // If view_callback is set, call that shit
        $cb = $this->settings['view_callback'];
        if( function_exists($this->settings['view_callback']) )
        {
            $this->settings['view_callback']($this->settings, $params, $sidebar);
        }
        else
        {
        	$this->html($this->settings, $params, $sidebar);
        }
            
        echo $do_wrapper ? $sidebar['after_widget'] : NULL;
    }

    /**
     * Administration Form
     * 
     * This method is called from within the wp-admin/widgets area when this
     * widget is placed into a sidebar. The resulting is a widget options form
     * that allows the administration to modify how the widget operates.
     * 
     * You do not need to adjust this method what-so-ever, it will parse the array
     * parameters given to it from the protected widget property of this class.
     *
     * @param array $instance
     * @return boolean
     */
    public function form( $instance )
    {
        // Fail if we don't have the good
        if( empty($this->settings['fields']) )
        {
            trigger_error('Quick_Widget Error: No fields provided for this widget. You, uh... You need those.');
            return FALSE;
        }
        
        $defaults = array(
                'id'        => '',
                'name'      => '',
                'desc'      => '',
                'type'      => 'text',
                'options'   => '',
                'default'   => '',
            );
        
        do_action(__CLASS__.'_before');

        foreach( $this->settings['fields'] as $field )
        {
            // Make sure defaults are set
            $field = wp_parse_args($field, $defaults);

            $meta = FALSE;
            if( isset($field['id']) && array_key_exists($field['id'], $instance) )
            {
                $meta = esc_attr($instance[$field['id']]);
            }

            if( isset($field['name']) && $field['name'] )
            {
                echo $field['name'], ':';
            }

            if( !empty($field['type']) )
            {
                $func = 'input_' . $field['type'];
                $do_label = $field['type']!=='custom' && $field['type']!=='metabox';

                if( method_exists($this, $func) )
                {
                    echo $do_label ? '<p><label for="' . $this->get_field_id($field['id']) . '">' : NULL;
                    $this->$func($instance, $field);
                    echo $do_label ? '</label></p>' : NULL;
                }
                else
                {
                    trigger_error("Quick_Widget Error: $func() is not defined.");
                    continue;
                }
            }
        }

        do_action(__CLASS__.'_before');

        return TRUE;
    }

    protected function input_text( $instance, $field )
    {
        extract($this->input($instance, $field));

?>

        <input type="text"
               name="<?php echo $this->get_field_name($id); ?>" 
               id="<?php echo $this->get_field_id($id); ?>" 
               value="<?php echo $value; ?>" 
               class="vibe_text" />
        <?php echo $desc; ?>

<?php

    }

    protected function input_textarea( $instance, $field )
    {
        extract($this->input($instance, $field));

?>

        <textarea name="<?php echo $this->get_field_name($id); ?>"
                  id="<?php echo $this->get_field_id($id); ?>" 
                  class="vibe_textarea"
                  cols="60" rows="4" style="width:97%"><?php echo $value; ?></textarea>
      	<?php echo $desc; ?>
<?php

    }

    protected function input_select( $instance, $field )
    {
        extract($this->input($instance, $field));
?>

        <select name="<?php echo $this->get_field_name($id); ?>"
                id="<?php echo $this->get_field_id($id); ?>" 
                class="vibe_select">

<?php foreach( $field['options'] as $name=>$option ): ?>
            <option value="<?php echo $name; ?>"<?php echo $name==$value ? ' selected="selected"' : NULL; ?>><?php echo $option; ?></option>

<?php endforeach; ?>
        </select>
        <?php echo $desc; ?>

<?php

    }

    protected function input_radio( $instance, $field )
    {
        extract($this->input($instance, $field));

        foreach( $field['options'] as $option ):

?>

        <input type="radio" <?php echo $value===$option['value'] ? ' checked="checked"' : NULL; ?>
               name="<?php echo $this->get_field_name($id); ?>" 
               value="<?php echo $option['value']; ?>" />
        <?php echo $option['name']; ?>

<?php endforeach; ?>
        <?php echo $desc; ?>

<?php

    }

    protected function input_checkbox( $instance, $field )
    {
        extract($this->input($instance, $field));

?>

        <input type="hidden" 
               name="<?php echo $this->get_field_name($id); ?>" 
               id="<?php echo $this->get_field_id($id); ?>" /> 
        <input class="vibe_checkbox" 
               type="checkbox" 
               name="<?php echo $this->get_field_name($id); ?>" 
               id="<?php echo $this->get_field_id($id); ?>"<?php echo !empty($value) ? ' checked="checked"' : ''; ?> /> 
        <?php echo $desc; ?>

<?php

    }

    protected function input( $instance, $field )
    {
        if( !isset($field['id']) || empty($field['id']) )
        {
            trigger_error("Quick_Widget Error: No ID provided for the input.");
            return;
        }

        if( isset($field['desc']) && !empty($field['desc']) )
        {
        	$desc = '<br /><span class="description">' . $field['desc'] . '</span>';
        }
        else
        {
        	$desc = NULL;
        }

        return array(
                'id'    => $field['id'],
                'value' => array_key_exists($field['id'], $instance) ? esc_attr($instance[$field['id']]) : '',
                'desc'	=> $desc,
            );
    }

    /**
     * Update the Administrative parameters
     * 
     * This function will merge any posted paramters with that of the saved
     * parameters. This ensures that the widget options never get lost. This
     * method does not need to be changed.
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    function update( $new_instance, $old_instance )
    {
        return wp_parse_args($new_instance, $old_instance);
    }

}
