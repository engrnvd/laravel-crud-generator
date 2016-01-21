<?php
/**
 * Created by naveedulhassan.
 * Date: 1/21/16
 * Time: 3:08 PM
 */
namespace Nvd\Crud;
use Illuminate\Support\MessageBag;

/**
 * Class Form
 * @package Nvd\Crud
 *
 * @property $name;
 * @property $value;
 *
 * @method Form label($label = "")
 * @method Form model($value = "")
 * @method Form type($value = "")
 * @method Form helpBlock($value = false)
 * @method Form options($value = false)
 * @method Form useOptionKeysForValues($value = false)
 */
class Form
{
    protected $attributes = [];
    protected $label;
    protected $helpBlock = true; // show help block or not
    protected $model; // model to be bound
    protected $type; // element type: select, input, textarea
    protected $options; // only for select element
    protected $useOptionKeysForValues; // only for select element

    public static function input( $name, $type = 'text' )
    {
        $elem = static::createElement($name);
        $elem->attributes['type'] = $type;
        return $elem;
    }

    public static function select( $name, $options, $useOptionKeysForValues = false )
    {
        $elem = static::createElement($name,"select");
        $elem->options = $options;
        $elem->useOptionKeysForValues = $useOptionKeysForValues;
        return $elem;
    }

    public static function textarea($name)
    {
        return static::createElement($name,"textarea");
    }

    public function attributes($value = null)
    {
        if($value and !is_array($value))
            throw new \Exception("Attributes should be an array.");

        if($value)
        {
            $this->attributes = array_merge($this->attributes, $value);
            return $this;
        }

        return $this->attributes;
    }

    public function show()
    {
        $this->setValue();
        $output = '<div class="form-group">';
        $output .= $this->label? "<label>{$this->label} <br>" : "";
        $output .= call_user_func([$this, "show".ucfirst($this->type)]);
        $output .= $this->label? "</label>" : "";

        if ( $this->helpBlock and $errors = \Session::get('errors', new MessageBag()) and $errors->has($this->name) )
        {
            $output .= '<span class="help-block text-danger">';
            $output .= $errors->first($this->name);
            $output .= '</span>';
        }

        $output .= "</div>";
        return $output;
    }

    protected function showInput()
    {
        $output = Html::startTag("input", $this->attributes, true );
        return $output;
    }

    protected function showSelect()
    {
        return Html::select( $this->name, $this->options, $this->attributes, $this->useOptionKeysForValues );
    }

    protected function showTextarea()
    {
        $output = Html::startTag( "textarea", $this->attributes );
        $output .= $this->value;
        $output .= Html::endTag("textarea");
        return $output;
    }

    protected static function createElement( $name, $type = 'input' )
    {
        $elem = new self;
        $elem->type = $type;
        $elem->name = $name;
        $elem->attributes['id'] = $name;
        $elem->attributes['class'] = 'form-control';
        $elem->label = ucwords( str_replace( "_"," ", $name ) );
        return $elem;
    }

    protected function setValue()
    {
        $this->value = old($this->name) or ($this->model and $this->model->{$this->name});
        return $this;
    }

    public function __call( $attr, $args = null )
    {
        if( !property_exists($this, $attr) )
            throw new \Exception("Method {$attr} does not exist.");

        if(count($args)){
            $this->$attr = $args[0];
            return $this;
        }

        return $this->$attr;
    }

    public function __set($name, $value)
    {
        if( in_array( $name, ['name','value'] ) )
        {
            $this->$name = $value;
            if( $name != 'value' or $this->type == 'input' ) // textarea and select should not have a value attribute
                $this->attributes[$name] = $value;
        }
    }

}