<?php

use Modules\YrzStatusHistory\CWidgetFieldYrzShThresholdsView;

$form = new CWidgetFormView($data);

$num_cells_field = $form->registerField(new CWidgetFieldIntegerBoxView($data['fields']['num_cells']));
$search_interval_select = $form->registerField(new CWidgetFieldSelectView($data['fields']['search_interval']));
$cell_width_field = $form->registerField(new CWidgetFieldIntegerBoxView($data['fields']['cell_width']));
$cell_height_field = $form->registerField(new CWidgetFieldIntegerBoxView($data['fields']['cell_height']));
$gap_horizontal_field = $form->registerField(new CWidgetFieldIntegerBoxView($data['fields']['gap_horizontal']));
$gap_vertical_field = $form->registerField(new CWidgetFieldIntegerBoxView($data['fields']['gap_vertical']));
$label_width_field = $form->registerField(new CWidgetFieldIntegerBoxView($data['fields']['label_width']))
  ->setFieldHint(makeWarningIcon(_('If set to 0, the width will be set on auto.')));
$value_empty_checkbox = $form->registerField(new CWidgetFieldCheckBoxView($data['fields']['value_empty_show']));
$value_empty_field = $form->registerField(new CWidgetFieldTextBoxView($data['fields']['value_empty_text']))
  ->setWidth(ZBX_TEXTAREA_SMALL_WIDTH);

$form
  ->addField(
    new CWidgetFieldMultiSelectItemView($data['fields']['itemid'])
  )
  ->addItem([
    $num_cells_field->getLabel(),
    (new CFormField([$num_cells_field->getView(), ' '._('with an interval of').' ', $search_interval_select->getView()]))
  ])
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
        (new CWidgetFieldYrzShThresholdsView($data['fields']['thresholds']))
          ->removeLabel()
      )
  )
  ->addFieldset(
		(new CWidgetFormFieldsetCollapsibleView(_('Advanced configuration')))
      ->addItem([
        $cell_width_field->getLabel(),
        (new CFormField([$cell_width_field->getView(), ' px']))
      ])
      ->addItem([
        $cell_height_field->getLabel(),
        (new CFormField([$cell_height_field->getView(), ' px']))
      ])
      ->addItem([
        $gap_horizontal_field->getLabel(),
        (new CFormField([$gap_horizontal_field->getView(), ' px']))
      ])
      ->addItem([
        $gap_vertical_field->getLabel(),
        (new CFormField([$gap_vertical_field->getView(), ' px']))
      ])
      ->addFieldsGroup(
        (new CWidgetFieldsGroupView(_('Labels')))
          ->addClass('fields-group-label')
          ->addField(
            new CWidgetFieldCheckBoxView($data['fields']['show_label'])
          )
          ->addField(
            new CWidgetFieldRadioButtonListView($data['fields']['label_align'])
          )
          ->addItem([
            $label_width_field->getLabel(),
            (new CFormField([$label_width_field->getView(), ' px']))
          ])
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
          ->addItem(
            new CTag('hr')
          )
          ->addField(
            new CWidgetFieldCheckBoxView($data['fields']['show_value'])
          )
          ->addItem([
            $value_empty_checkbox->getLabel(),
            (new CFormField([$value_empty_checkbox->getView(), $value_empty_field->getView()]))
          ])
          ->addField(
            new CWidgetFieldCheckBoxView($data['fields']['show_units'])
          )
          ->addField(
            new CWidgetFieldIntegerBoxView($data['fields']['value_digits'])
          )
          ->addField(
            new CWidgetFieldRadioButtonListView($data['fields']['cell_align'])
          )
          ->addItem(
            new CTag('hr')
          )
          ->addField(
            new CWidgetFieldRadioButtonListView($data['fields']['show_date'])
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
              .' a color interpolation between the two thresholds colors.'))
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