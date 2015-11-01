<?php

/**
 * A model class for tracking appliance runs.
 */
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * A model class for tracking appliance runs.
 *
 * @property \DateTime $started_at The time the appliance started running.
 * @property \DateTime $completed_at The time the appliance completed running.
 * @property boolean $is_running Whether or not the appliance is currently running.
 */
class Run extends Model
{
	/**
	 * Get the Appliance associated with this run.
	 *
	 * @return \App\Model\Appliance The Appliance associated with this run.
	 */
	public function appliance() {
		return $this->belongsTo('App\Model\Appliance');
	}

	/**
	 * Get the LoadCurve associated with this run.
	 *
	 * @return \App\Model\LoadCurve The LoadCurve associated with this run.
	 */
	public function loadCurve() {
		return $this->belongsTo('App\Model\LoadCurve');
	}
}
