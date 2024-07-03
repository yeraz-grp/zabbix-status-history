<?php
?>

window.widget_yrz_status_history_form = new class {

  init({thresholds}) {
    this._form = document.getElementById('widget-dialogue-form');

    this._showLabel = document.getElementById('show_label');
    this._labelWidth = document.getElementById('label_width');
    this._showValue = document.getElementById('show_value');
    this._valueEmptyShow = document.getElementById('value_empty_show');
    this._valueEmptyText = document.getElementById('value_empty_text');
    this._showUnits = document.getElementById('show_units');
    this._valueDigits = document.getElementById('value_digits');
    this._showDate = document.getElementById('show_date');
    this._colorInterval = document.getElementById('color_interval');
    this._colorInterpolation = document.getElementById('color_interpolation');
    this._showLegend = document.getElementById('show_legend');

    this._showLabel.addEventListener('change', () => this.updateForm());
    this._showValue.addEventListener('change', () => this.updateForm());
    this._valueEmptyShow.addEventListener('change', () => this.updateForm());
    this._showDate.addEventListener('change', () => this.updateForm());
    this._colorInterval.addEventListener('change', () => this.updateForm());
    this._showLegend.addEventListener('change', () => this.updateForm());

    this._latest_threshold = (thresholds.length == 0) ? 80 : parseInt(thresholds.slice(-1)[0].threshold) + 10;

    this._colorpickerInit();
    this.updateForm();
  }

  _colorpickerInit() {
    for (const colorpicker of jQuery('.<?= ZBX_STYLE_COLOR_PICKER ?> input')) {
      jQuery(colorpicker).colorpicker(
        { 
          appendTo: '.overlay-dialogue-body',
          use_default: true,
          onUpdate: window.setIndicatorColor
        }
      );
    }

    jQuery('#thresholds_table_thresholds').on("afteradd.dynamicRows", (e) => {
      this._addThreshold(e.target);
    });
  }

  _addThreshold(tableThresholds) {
    const used_colors = [];

    for (const color of this._form.querySelectorAll('.<?= ZBX_STYLE_COLOR_PICKER ?> input')) {
      if (color.value !== '') {
        used_colors.push(color.value);
      }
    }

    const lastThreshold = jQuery('TR.form_row', tableThresholds).last();

    jQuery('INPUT', lastThreshold).last().val(this._latest_threshold);

    this._latest_threshold += 10;

    jQuery('.<?= ZBX_STYLE_COLOR_PICKER ?> input', lastThreshold).colorpicker(
      { appendTo: '.overlay-dialogue-body'}
    );

    jQuery.colorpicker('set_color', colorPalette.getNextColor(used_colors));
  }

  updateForm() {
    for (const element of this._form.querySelectorAll('.adv-conf-item')) {
      element.style.display = this._advanced_configuration.checked ? '' : 'none';
    }

    for (const labelAlignButtons of document.querySelectorAll('#label_align input')) {
      labelAlignButtons.disabled = !this._showLabel.checked;
    }
    this._labelWidth.disabled = !this._showLabel.checked;

    for (const cellAlignButtons of document.querySelectorAll('#cell_align input')) {
      cellAlignButtons.disabled = !this._showValue.checked;
    }
    this._valueEmptyShow.disabled = !this._showValue.checked;
    this._valueDigits.disabled = !this._showValue.checked;
    this._showUnits.disabled = !this._showValue.checked;

    this._valueEmptyText.disabled = !this._showValue.checked || !this._valueEmptyShow.checked;

    for (const dateFormatButtons of document.querySelectorAll('#date_format input')) {
      dateFormatButtons.disabled = !this._showDate.checked;
    }

    this._colorInterpolation.disabled = !this._colorInterval.checked;

    for (const legendLayoutButtons of document.querySelectorAll('#legend_layout input')) {
      legendLayoutButtons.disabled = !this._showLegend.checked;
    }
  }

};