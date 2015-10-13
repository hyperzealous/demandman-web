<?php

/**
 * Decides if appliances are allowed to run.
 *
 * Decides based on demand.
 */
namespace App\Services;

use App\ActionRequest;
use App\ActionResponse;
use App\Services\Analyzer;
use App\Services\CostCalculator;
use App\Services\Predictor;
use App\Services\ApiMessenger;

/**
 * Decides if appliances are allowed to run.
 *
 * Decides based on demand.
 */
class DemandManager implements Manager
{
	protected $applianceStore;
	protected $analyzer;
	protected $costCalculator;
	protected $predictor;
	protected $messenger;
	
	public function __construct(ApplianceStore $applianceStore,
			Analyzer $analyzer,
			CostCalculator $costCalculator,
			Predictor $predictor,
			ApiMessenger $messenger) {
		$this->applianceStore = $applianceStore;
		$this->analyzer = $analyzer;
		$this->costCalculator = $costCalculator;
		$this->predictor = $predictor;
		$this->messenger = $messenger;
	}

	public function startAppliance(ActionRequest $request) {
		$appliance = $this->applianceStore->get($request->applianceId());
		$predicted_curve = $this->predictor->predictAggregate([$appliance]);
		$cost = $this->costCalculator->demandCost($predicted_curve);
//		if ($cost > $this->getMaxAllowableCost()) {
//			return false;
//		} else {
//			return true;
//		}
		return true;
	}

	public function stopAppliance(ActionRequest $request) {
		return true;
	}

	public function pauseAppliance(ActionRequest $request) {
		return true;
	}

	public function resumeAppliance(ActionRequest $request) {
		return true;
	}

	public function wakeAppliance(ActionRequest $request) {
		return true;
	}

	public function getMaxAllowableDemand() {

	}

	public function getMaxAllowableCost() {

	}

	public function confirmStart(ActionRequest $request, ActionResponse $response) {
		if ($response->succeeded() || $response->approved()) {
			//remove timeout event from queue
			//done?? Or mark request as complete / successful maybe.
		} else {
			//does the manager care if it fails?
		}
	}

	public function confirmStop(ActionRequest $request, ActionResponse $response) {
	}

	public function confirmPause(ActionRequest $request, ActionResponse $response) {
	}

	public function confirmResume(ActionRequest $request, ActionResponse $response) {
	}

	public function confirmWake(ActionRequest $request, ActionResponse $response) {
	}
}
