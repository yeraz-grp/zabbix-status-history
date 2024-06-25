<?php

namespace Modules\YrzStatusHistory;

use Zabbix\Core\CWidget;

class Widget extends CWidget {

  public const YRZ_SH_OFF = 0;
  public const YRZ_SH_ON = 1;

  public const YRZ_SH_AGG_FUNC_FIRST = 0;
  public const YRZ_SH_AGG_FUNC_LAST = 1;
  public const YRZ_SH_AGG_FUNC_MIN = 2;
  public const YRZ_SH_AGG_FUNC_MAX = 3;
  public const YRZ_SH_AGG_FUNC_AVG = 4;

  public const YRZ_SH_LABEL_ALIGN_LEFT = 0;
  public const YRZ_SH_LABEL_ALIGN_RIGHT = 1;

  public const YRZ_SH_CELL_POSITION_LEFT = 0;
  public const YRZ_SH_CELL_POSITION_RIGHT = 1;

  public const YRZ_SH_CELL_ORDER_NEWEST = 0;
  public const YRZ_SH_CELL_ORDER_OLDEST = 1;

  public const YRZ_SH_CELL_ALIGN_LEFT = 0;
  public const YRZ_SH_CELL_ALIGN_CENTER = 1;
  public const YRZ_SH_CELL_ALIGN_RIGHT = 2;

  public const YRZ_SH_DATE_FORMAT_ALL = 0;
  public const YRZ_SH_DATE_FORMAT_WEEKLY = 1;

  public const YRZ_SH_LEGEND_LAYOUT_TABLE =	0;
  public const YRZ_SH_LEGEND_LAYOUT_LIST = 1;

	public function getDefaultName(): string {
		return _('Yeraz | Status History');
	}
  
}