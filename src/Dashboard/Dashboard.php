<?php

namespace Khill\Lavacharts\Dashboard;

use \Khill\Lavacharts\Utils;
use \Khill\Lavacharts\Values\Label;
use \Khill\Lavacharts\Dashboard\Bindings\BindingFactory;
use \Khill\Lavacharts\Exceptions\InvalidLabel;
use \Khill\Lavacharts\Exceptions\InvalidFunctionParam;

class Dashboard implements \JsonSerializable
{
    /**
     * Google's dashboard version
     *
     * @var string
     */
    const VERSION = '1';

    /**
     * Javascript chart package.
     *
     * @var string
     */
    const VIZ_PACKAGE = 'controls';

    /**
     * Javascript chart class.
     *
     * @var string
     */
    const VIZ_CLASS = 'google.visualization.Dashboard';

    /**
     * The dashboard's unique label.
     *
     * @var \Khill\Lavacharts\Values\Label
     */
    private $label = null;

    /**
     * Arry of Binding objects, mapping controls to charts.
     *
     * @var array
     */
    private $bindings = [];

    /**
     * Builds a new Dashboard with identifying label.
     *
     * @param  string $label
     * @return self
     */
    public function __construct(Label $label)
    {
        $this->label = $label;
        $this->bindingFactory = new BindingFactory;
    }

    /**
     * Binds ControlWrappers to ChartWrappers in the dashboard.
     *
     * - A OneToOne binding is created if single wrappers are passed.
     * - If a single ControlWrapper is passed with an array of ChartWappers,
     *   a OneToMany binding is created.
     * - If an array of ControlWrappers is passed with one ChartWrapper, then
     *   a ManyToOne binding is created.
     *
     * @param  mixed $controlWraps
     * @param  mixed $chartWraps
     * @return self
     */
    public function bind($controlWraps, $chartWraps)
    {
        $this->bindings[] = $this->bindingFactory->create($controlWraps, $chartWraps);

        return $this;
    }

    /**
     * Fetch the dashboard's bindings.
     *
     * @return array
     */
    public function getBindings()
    {
        return $this->bindings;
    }

    /**
     * Returns the dashboard label.
     *
     * @since  3.0.0
     * @return \Khill\Lavacharts\Values\Label
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Custom JSON serialization of the Dashboard.
     *
     * @return string JSON
     */
    public function jsonSerialize()
    {
        return $this->bindings;
    }

    /**
     * Add array of bindings.
     *
     * bind will use this method if there are OneToMany or ManyToOne bindings
     *
     * @param array $bindings
     */
    private function addArrayOfBindings($bindings)
    {
        foreach ($bindings as $binding) {
            $this->bind($binding[0], $binding[1], $binding[2]);
        }
    }

}