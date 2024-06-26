<?php

namespace Modules\YrzStatusHistory;

use Zabbix\Widgets\Fields\CWidgetFieldThresholds;

class CWidgetFieldYrzShThresholds extends CWidgetFieldThresholds {

  public function __construct(string $name, string $label = null, bool $is_binary_units = false) {
		parent::__construct($name, $label, $is_binary_units);

		$this
			->setDefault(self::DEFAULT_VALUE)
			->setValidationRules(['type' => API_OBJECTS, 'uniq' => [['threshold']], 'fields' => [
				'color'		=> ['type' => API_COLOR, 'flags' => API_REQUIRED | API_NOT_EMPTY],
				'threshold'	=> ['type' => API_NUMERIC, 'flags' => API_REQUIRED],
        'text' => ['type' => API_STRING_UTF8]
			]]);
	}

  public function toApi(array &$widget_fields = []): void {
		foreach ($this->getValue() as $index => $value) {
			$widget_fields[] = [
				'type' => $this->save_type,
				'name' => $this->name.'.'.$index.'.color',
				'value' => $value['color']
			];
			$widget_fields[] = [
				'type' => $this->save_type,
				'name' => $this->name.'.'.$index.'.threshold',
				'value' => $value['threshold']
			];
      $widget_fields[] = [
				'type' => $this->save_type,
				'name' => $this->name.'.'.$index.'.text',
				'value' => isset($value['text']) ? $value['text'] : ''
			];
		}
	}

}