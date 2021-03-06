<?php

/**
 * An interface for appliance action requests.
 */
namespace App;

/**
 * An interface for appliance action requests.
 */
interface ActionRequest
{
	/**
	 * Mark the request as approved.
	 *
	 * @return void
	 */
	public function approve();

	/**
	 * Mark the request as denied.
	 *
	 * @return void
	 */
	public function deny();

	/**
	 * Get the request id.
	 *
	 * @return mixed The request id.
	 */
	public function requestId();

	/**
	 * Mark the request as completed successfully.
	 *
	 * @return void
	 */
	public function succeeded();

	/**
	 * Mark the request as incomplete/failed.
	 *
	 * @return void
	 */
	public function failed();

	/**
	 * Get the action.
	 *
	 * @return string The action.
	 */
	public function getAction();

	/**
	 * Get the appliance id.
	 *
	 * @return int The appliance id.
	 */
	public function applianceId();

	/**
	 * @return \Carbon\Carbon The time the appliance wants to start.
	 */
	public function getStartTime();
}
