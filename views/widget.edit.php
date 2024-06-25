<?php

$form = new CWidgetFormView($data);

$form
  ->addField(
    new CWidgetFieldMultiSelectItemView($data['fields']['itemid'])
  )
  ->addField(
    new CWidgetFieldIntegerBoxView($data['fields']['num_days'])
  )
  ->addField(
    new CWidgetFieldSelectView($data['fields']['agg_func'])
  )
  ->addField(
    new CWidgetFieldColorView($data['fields']['base_color'])
  )
  ->addFieldsGroup(
    (new CWidgetFieldsGroupView(_('Thresholds')))
      ->addClass('fields-group-thresholds')
      ->addField(
        (new CWidgetFieldThresholdsView($data['fields']['thresholds']))
          ->removeLabel()
      )
  )
  ->addFieldset(
		(new CWidgetFormFieldsetCollapsibleView(_('Advanced configuration')))
      ->addField(
        new CWidgetFieldIntegerBoxView($data['fields']['cell_width'])
      )
      ->addField(
        new CWidgetFieldIntegerBoxView($data['fields']['cell_height'])
      )
      ->addField(
        new CWidgetFieldIntegerBoxView($data['fields']['gap_horizontal'])
      )
      ->addField(
        new CWidgetFieldIntegerBoxView($data['fields']['gap_vertical'])
      )
      ->addFieldsGroup(
        (new CWidgetFieldsGroupView(_('Labels')))
          ->addClass('fields-group-label')
          ->addField(
            new CWidgetFieldCheckBoxView($data['fields']['show_label'])
          )
          ->addField(
            new CWidgetFieldRadioButtonListView($data['fields']['label_align'])
          )
          ->addField(
            (new CWidgetFieldIntegerBoxView($data['fields']['label_width']))
            ->setFieldHint(
              makeWarningIcon(_('If set to 0, the width will be set on auto.'))
            )
          )
      )
      ->addFieldsGroup(
        (new CWidgetFieldsGroupView(_('Status cells')))
          ->addClass('fields-group-statuscell')
          ->addField(
            new CWidgetFieldRadioButtonListView($data['fields']['cell_position'])
          )
          ->addField(
            new CWidgetFieldRadioButtonListView($data['fields']['cell_order'])
          )
          ->addField(
            new CWidgetFieldCheckBoxView($data['fields']['show_value'])
          )
          ->addField(
            new CWidgetFieldIntegerBoxView($data['fields']['value_round'])
          )
          ->addField(
            new CWidgetFieldRadioButtonListView($data['fields']['cell_align'])
          )
          ->addItem(
            new CTag('hr')
          )
          ->addField(
            new CWidgetFieldCheckBoxView($data['fields']['show_date'])
          )
          ->addField(
            new CWidgetFieldRadioButtonListView($data['fields']['date_format'])
          )
          ->addItem(
            new CTag('hr')
          )
          ->addField(
            (new CWidgetFieldCheckBoxView($data['fields']['color_interval']))
            ->setFieldHint(
              makeWarningIcon(_('If the value of the cell is between 2 thresholds,'
              .' it will set the color of the previous threshold in the interval.'))
            )
          )
          ->addField(
            (new CWidgetFieldCheckBoxView($data['fields']['color_interpolation']))
            ->setFieldHint(
              makeWarningIcon(_('If activated, it replace the color interval with'
              .'a color interpolation between the two thresholds colors.'))
            )
          )
          ->addField(
            (new CWidgetFieldCheckBoxView($data['fields']['color_exceed']))
            ->setFieldHint(
              makeWarningIcon(_('If the value is below the lowest threshold'
              .' or higher than the highest threshold, the color of said'
              .' threshold will be set rather than setting the default color.'))
            )
          )
      )
      ->addFieldsGroup(
        (new CWidgetFieldsGroupView(_('Legend')))
          ->addClass('fields-group-legend')
          ->addField(
            new CWidgetFieldCheckBoxView($data['fields']['show_legend'])
          )
          ->addField(
            new CWidgetFieldRadioButtonListView($data['fields']['legend_layout'])
          )
      )
  )
  ->includeJsFile('widget.edit.js.php')
	->addJavaScript('widget_yrz_status_history_form.init('.json_encode([
		'thresholds' => ($data['fields']['thresholds'])->getValue()
	], JSON_THROW_ON_ERROR).');')
  ->show();