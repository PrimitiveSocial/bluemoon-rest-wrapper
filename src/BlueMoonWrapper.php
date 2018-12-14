<?php

namespace PrimitiveSocial\BlueMoonWrapper;

use GuzzleHttp\Client;
use Carbon\Carbon;

class BlueMoonWrapper
{

	protected $clientUrl;

	protected $clientSecret;

	protected $clientId;

	protected $clientUsername;

	protected $clientPassword;

	protected $clientLicense;

	protected $token;

	protected $propertyId;

	protected $externalId;

	protected $applicationId;

	private $client;

	private $headers;

	private $clientData;

	private $parseData;

	private $applicationFields;

	private $booleanFields;

	public function __construct($clientLicense = null, $clientUrl = null, $clientSecret = null, $clientId = null, $clientUsername = null, $clientPassword = null) {

		$this->clientUrl = $clientUrl ?: config('bluemoon.rest.url');
		$this->clientSecret = $clientSecret ?: config('bluemoon.rest.secret');
		$this->clientId = $clientId ?: config('bluemoon.rest.id');
		$this->clientUsername = $clientUsername ?: config('bluemoon.rest.username');
		$this->clientPassword = $clientPassword ?: config('bluemoon.rest.password');
		$this->clientLicense = $clientLicense ?: config('bluemoon.rest.license');

		$this->client = new Client(['base_uri' => $this->clientUrl]);
		
		$this->headers = [
			'Accept' => 'application/json',
			'Content-Type' => 'application/json',
			'Provider' => 'legacy'
		];
		
		$this->clientData = [
			'username' => $this->clientUsername,
			'password' => $this->clientPassword,
			'license' => $this->clientLicense,
			'grant_type' => 'password',
			'client_id' => $this->clientId,
			'scope' => '*',
			'client_secret' => $this->clientSecret,
		];

		$token = $this->login();

		$this->headers['Authorization'] = 'Bearer ' . $token;

		$this->applicationFields = array(
			'ach_account_holder_address',
			'ach_account_holder_city',
			'ach_account_holder_name',
			'ach_account_holder_st',
			'ach_account_holder_zip',
			'ach_account_no',
			'ach_routing_no',
			'applicant_birth_date',
			'applicant_cell_phone',
			'applicant_dr_lic_no',
			'applicant_dr_lic_st',
			'applicant_first_name',
			'applicant_former_name',
			'applicant_gender',
			'applicant_government_id',
			'applicant_government_id_type',
			'applicant_home_phone',
			'applicant_last_name',
			'applicant_middle_name',
			'applicant_ssn',
			'application_date',
			'application_deposit',
			'application_name',
			'apply_address',
			'charge_end_unit_on_market',
			'charge_end_unit_rented',
			'co_applicant_another',
			'credit_problems_explanation',
			'credit_report_cost',
			'do_you_smoke',
			'emergency_affidavit_above',
			'emergency_affidavit_child',
			'emergency_affidavit_spouse',
			'emergency_contact_address',
			'emergency_contact_cell_phone',
			'emergency_contact_city',
			'emergency_contact_email',
			'emergency_contact_home_phone',
			'emergency_contact_name',
			'emergency_contact_relation',
			'emergency_contact_st',
			'emergency_contact_work_phone',
			'emergency_contact_zip',
			'felony_convict_information',
			'felony_convict_resolved',
			'have_you_been_convicted_felony',
			'have_you_been_evicted',
			'have_you_been_sued_prop_dmgs',
			'have_you_been_sued_rent',
			'have_you_broken_rent_agreemnt',
			'have_you_declared_bankruptcy',
			'have_you_owned_home',
			'internet_site',
			'nonrefundable_application_fee',
			'other_referral_name',
			'previous_aliases_usage_term',
			'processing_cost',
			'referral_refer_name',
			'referral_social_media_name',
			'referral_type',
		);

		$this->booleanFields = array(
			'have_you_been_convicted_felony',
			'have_you_been_evicted',
			'have_you_been_sued_prop_dmgs',
			'have_you_been_sued_rent',
			'have_you_broken_rent_agreemnt',
			'have_you_declared_bankruptcy',
			'have_you_owned_home',
		);

	}

	public function setPropertyId($id) {

		$this->propertyId = $id;
		return $this;

	}

	public function setExternalId($id) {

		$this->externalId = $id;
		return $this;

	}

	public function setApplicationId($id) {

		$this->applicationId = $id;
		return $this;

	}

	public function getApplications() {

		$data = [
			// Test = 6060, 70
			'search' => 'property_id:' . $this->propertyId . ';external_id:' . $this->externalId,
			'searchJoin' => 'and'
		];

		$url = '/api/application?search=' . $data['search'] . '&searchJoin=and';

		$response = $this->client->get($url, [
			'headers' => $this->headers,
			'http_errors' => false,
		]);

		$statusCode = $response->getStatusCode();
		
		$data = json_decode($response->getBody(), true);

		if($statusCode == 200){

			return $data['data'];

		}

		return false;

	}

	public function getApplication() {

		$url = '/api/application/' . $this->applicationId;

		$response = $this->client->get($url, [
			'headers' => $this->headers,
			'http_errors' => false,
		]);

		$statusCode = $response->getStatusCode();
		
		$data = json_decode($response->getBody(), true);

		if($statusCode == 200){

			return $data;

		}

		return false;

	}

	public function getApplicationFields() {

		// https://api.bluemoonforms.com/api/application/fields?property_id=74328
		$uri = 'api/application/fields?property_id=' . $this->propertyId;

		$response = $this->client->get($uri, [
			'headers' => $this->headers,
			'http_errors' => false,
		]);

		$statusCode = $response->getStatusCode();
		
		$data = json_decode($response->getBody(), true);

		if($statusCode == 200){

			return $data;

		}

		return false;

	}

	public function getLeaseFields() {

		// https://api.bluemoonforms.com/api/application/fields?property_id=74328
		$uri = 'api/lease/fields/' . $this->propertyId;

		$response = $this->client->get($uri, [
			'headers' => $this->headers,
			'http_errors' => false,
		]);

		$statusCode = $response->getStatusCode();
		
		$data = json_decode($response->getBody(), true);

		if($statusCode == 200){

			return $data;

		}

		return false;

	}

	public function getApplicationAndParse() {

		$appData = $this->getApplication();

		if($appData) {

			$returnData = $this->parse($appData['data']);

			return $returnData;
		}

		return false;

	}

	private function parse($data) {

		$out = new \stdClass();

		// Use this to abstract out co_applicant, occupant, pet, and vehicle.
		$out->coapplicants = $out->occupants = $out->pets = $out->vehicles = $out->income = $out->employers = $out->residences = array();

		// Get rid of the ones we don't need
		unset($data['property_id'], $data['viewed'], $data['signed'], $data['updated_at'], $data['created_at'], $data['first_name'], $data['last_name'], $data['esign_id'], $data['signer_key'], $data['external_id']);

		$out->application_id = $data['id'];
		$out->unit_number = $data['unit_number'];
		$out->has_credit_ach = $data['has_credit_ach'];

		$this->parseData = $data['data'];

		foreach ($this->applicationFields as $field) {
			$out->{$field} = $this->valueOrNull($field);
		}

		// process co_applicants
		for ($i = 1; $i < 6; $i++) { 

			$c = new \stdClass();
			$c->email = $this->valueOrNull('co_applicant_' . $i . '_email');
			$c->name = $this->valueOrNull('co_applicant_' . $i . '_name');

			unset($this->parseData['co_applicant_' . $i . '_email'], $this->parseData['co_applicant_' . $i . '_name']);
	
			$out->coapplicants[] = $c;
		}

		// process occupants
		for ($i = 1; $i < 7; $i++) {
			
			$o = new \stdClass();

			$o->birth_date = $this->valueOrNull("occupant_" . $i . "_birth_date");
			$o->dr_lic_no = $this->valueOrNull("occupant_" . $i . "_dr_lic_no");
			$o->dr_lic_st = $this->valueOrNull("occupant_" . $i . "_dr_lic_st");
			$o->gov_id_no = $this->valueOrNull("occupant_" . $i . "_gov_id_no");
			$o->gov_id_type = $this->valueOrNull("occupant_" . $i . "_gov_id_type");
			$o->name = $this->valueOrNull("occupant_" . $i . "_name");
			$o->relation = $this->valueOrNull("occupant_" . $i . "_relation");
			$o->ssn = $this->valueOrNull("occupant_" . $i . "_ssn");

			unset($this->parseData["occupant_" . $i . "_birth_date"], $this->parseData["occupant_" . $i . "_dr_lic_no"],$this->parseData["occupant_" . $i . "_dr_lic_st"],$this->parseData["occupant_" . $i . "_gov_id_no"],$this->parseData["occupant_" . $i . "_gov_id_type"],$this->parseData["occupant_" . $i . "_name"],$this->parseData["occupant_" . $i . "_relation"],$this->parseData["occupant_" . $i . "_ssn"]);

			$out->occupants[] = $o;
		}

		// process pets
		for ($i = 1; $i < 3; $i++) { 
			$p = new \stdClass();

			$p->age = $this->valueOrNull("pet_" . $i . "_age");
			$p->assist = $this->valueOrNull("pet_" . $i . "_assist");
			$p->breed = $this->valueOrNull("pet_" . $i . "_breed");
			$p->color = $this->valueOrNull("pet_" . $i . "_color");
			$p->gender = $this->valueOrNull("pet_" . $i . "_gender");
			$p->name = $this->valueOrNull("pet_" . $i . "_name");
			$p->type = $this->valueOrNull("pet_" . $i . "_type");
			$p->weight = $this->valueOrNull("pet_" . $i . "_weight");

			unset($this->parseData["pet_" . $i . "_age"], $this->parseData["pet_" . $i . "_assist"], $this->parseData["pet_" . $i . "_breed"], $this->parseData["pet_" . $i . "_color"], $this->parseData["pet_" . $i . "_gender"], $this->parseData["pet_" . $i . "_name"], $this->parseData["pet_" . $i . "_type"], $this->parseData["pet_" . $i . "_weight"]);

			$out->pets[] = $p;
		}

		// process income
		for ($i = 1; $i < 3; $i++) { 
			$p = new \stdClass();

			$p->amount = $this->valueOrNull('income_' . $i . '_amount');
			$p->source = $this->valueOrNull('income_' . $i . '_source');
			$p->type = $this->valueOrNull('income_' . $i . '_type');

			unset($this->parseData['income_' . $i . '_amount'], $this->parseData['income_' . $i . '_source'], $this->parseData['income_' . $i . '_type']);

			$out->income[] = $p;
		}
		
		// process vehicles
		for ($i = 1; $i < 5; $i++) { 
			$v = new \stdClass();

			$v->color = $this->valueOrNull("vehicle_" . $i . "_color");
			$v->license_no = $this->valueOrNull("vehicle_" . $i . "_license_no");
			$v->license_st = $this->valueOrNull("vehicle_" . $i . "_license_st");
			$v->make = $this->valueOrNull("vehicle_" . $i . "_make");
			$v->model = $this->valueOrNull("vehicle_" . $i . "_model");
			$v->year = $this->valueOrNull("vehicle_" . $i . "_year");

			unset($this->parseData["vehicle_" . $i . "_color"], $this->parseData["vehicle_" . $i . "_license_no"], $this->parseData["vehicle_" . $i . "_license_st"], $this->parseData["vehicle_" . $i . "_make"], $this->parseData["vehicle_" . $i . "_model"], $this->parseData["vehicle_" . $i . "_year"]);

			$out->vehicles[] = $v;
		}

		// Process employers
		// Current
		$currentEmployer = new \stdClass();
		$currentEmployer->name = $this->valueOrNull('current_employer');
		$currentEmployer->status = $this->valueOrNull('current_employer') ? 'current' : null;
		$currentEmployer->address = $this->valueOrNull('current_employer_address');
		$currentEmployer->city = $this->valueOrNull('current_employer_city');
		$currentEmployer->end_date = $this->valueOrNull('current_employer_end_date');
		$currentEmployer->gross_inc = $this->valueOrNull('current_employer_gross_inc');
		$currentEmployer->phone = $this->valueOrNull('current_employer_phone');
		$currentEmployer->position = $this->valueOrNull('current_employer_position');
		$currentEmployer->st = $this->valueOrNull('current_employer_st');
		$currentEmployer->start_date = $this->valueOrNull('current_employer_start_date');
		$currentEmployer->supervisor = $this->valueOrNull('current_employer_supervisor');
		$currentEmployer->supr_phone = $this->valueOrNull('current_employer_supr_phone');
		$currentEmployer->zip = $this->valueOrNull('current_employer_zip');
		
		// Previous
		$previousEmployer = new \stdClass();
		$previousEmployer->name = $this->valueOrNull('previous_employer');
		$previousEmployer->status = $this->valueOrNull('previous_employer') ? 'previous' : null;
		$previousEmployer->address = $this->valueOrNull('previous_employer_address');
		// Begin date vs. start date....why
		$previousEmployer->start_date = $this->valueOrNull('previous_employer_begin_date');
		$previousEmployer->city = $this->valueOrNull('previous_employer_city');
		$previousEmployer->end_date = $this->valueOrNull('previous_employer_end_date');
		$previousEmployer->gross_inc = $this->valueOrNull('previous_employer_gross_inc');
		$previousEmployer->phone = $this->valueOrNull('previous_employer_phone');
		$previousEmployer->position = $this->valueOrNull('previous_employer_position');
		$previousEmployer->st = $this->valueOrNull('previous_employer_st');
		$previousEmployer->supervisor = $this->valueOrNull('previous_employer_supervisor');
		$previousEmployer->supr_phone = $this->valueOrNull('previous_employer_supr_phone');
		$previousEmployer->zip = $this->valueOrNull('previous_employer_zip');

		unset($this->parseData['current_employer'],$this->parseData['current_employer_address'],$this->parseData['current_employer_city'],$this->parseData['current_employer_end_date'],$this->parseData['current_employer_gross_inc'],$this->parseData['current_employer_phone'],$this->parseData['current_employer_position'],$this->parseData['current_employer_st'],$this->parseData['current_employer_start_date'],$this->parseData['current_employer_supervisor'],$this->parseData['current_employer_supr_phone'],$this->parseData['current_employer_zip'],$this->parseData['previous_employer'],$this->parseData['previous_employer_address'],$this->parseData['previous_employer_city'],$this->parseData['previous_employer_end_date'],$this->parseData['previous_employer_gross_inc'],$this->parseData['previous_employer_phone'],$this->parseData['previous_employer_position'],$this->parseData['previous_employer_st'],$this->parseData['previous_employer_supervisor'],$this->parseData['previous_employer_supr_phone'],$this->parseData['previous_employer_zip']);

		$out->employers = array($currentEmployer, $previousEmployer);

		// Process residences
		// Current
		$currentResidence = new \stdClass();
		$currentResidence->address = $this->valueOrNull('current_address');
		$currentResidence->apartment = $this->valueOrNull('current_apartment');
		$currentResidence->city = $this->valueOrNull('current_city');
		$currentResidence->date_moved_in = $this->valueOrNull('current_date_moved_in');
		$currentResidence->date_moved_out = $this->valueOrNull('current_date_moved_out');
		$currentResidence->email = $this->valueOrNull('current_email');
		$currentResidence->owner_manager = $this->valueOrNull('current_owner_manager');
		$currentResidence->owner_phone = $this->valueOrNull('current_owner_phone');
		$currentResidence->reason_for_leaving = $this->valueOrNull('current_reason_for_leaving');
		$currentResidence->rent = $this->valueOrNull('current_rent');
		$currentResidence->residence_status = $this->valueOrNull('current_residence_status');
		$currentResidence->st = $this->valueOrNull('current_st');
		$currentResidence->zip = $this->valueOrNull('current_zip');

		$previousResidence = new \stdClass();
		$previousResidence->address = $this->valueOrNull('previous_address');
		$previousResidence->apartment = $this->valueOrNull('previous_apartment');
		$previousResidence->city = $this->valueOrNull('previous_city');
		$previousResidence->date_moved_in = $this->valueOrNull('previous_date_moved_in');
		$previousResidence->date_moved_out = $this->valueOrNull('previous_date_moved_out');
		$previousResidence->owner_manager = $this->valueOrNull('previous_owner_manager');
		$previousResidence->owner_phone = $this->valueOrNull('previous_owner_phone');
		$previousResidence->reason_for_leaving = $this->valueOrNull('previous_reason_for_leaving');
		$previousResidence->rent = $this->valueOrNull('previous_rent');
		$previousResidence->residence_status = $this->valueOrNull('previous_residence_status');
		$previousResidence->st = $this->valueOrNull('previous_st');
		$previousResidence->zip = $this->valueOrNull('previous_zip');

		unset($this->parseData['current_address'],$this->parseData['current_apartment'],$this->parseData['current_city'],$this->parseData['current_date_moved_in'],$this->parseData['current_date_moved_out'],$this->parseData['current_email'],$this->parseData['current_owner_manager'],$this->parseData['current_owner_phone'],$this->parseData['current_reason_for_leaving'],$this->parseData['current_rent'],$this->parseData['current_residence_status'],$this->parseData['current_st'],$this->parseData['current_zip'],$this->parseData['previous_address'],$this->parseData['previous_apartment'],$this->parseData['previous_city'],$this->parseData['previous_date_moved_in'],$this->parseData['previous_date_moved_out'],$this->parseData['previous_email'],$this->parseData['previous_owner_manager'],$this->parseData['previous_owner_phone'],$this->parseData['previous_reason_for_leaving'],$this->parseData['previous_rent'],$this->parseData['previous_residence_status'],$this->parseData['previous_st'],$this->parseData['previous_zip']);

		$out->residences = array($currentResidence, $previousResidence);

		foreach ($data as $key => $value) {
			$out->{$key} = $value;
		}

		return $out;

	}

	public function getToken() {

		$client = new Client(['base_uri' => $this->clientUrl]);

		$headers = [
			'Accept' => 'application/json',
			'Content-Type' => 'application/json',
			'Provider' => 'legacy'
		];

		$data = [
			'username' => $this->clientUsername,
			'password' => $this->clientPassword,
			'license' => $this->clientLicense,
			'grant_type' => 'password',
			'client_id' => $this->clientId,
			'client_secret' => $this->clientSecret,
		];

		$response = $client->post('/oauth/token', [
			'headers' => $headers,
			'http_errors' => false,
			'json' => $data
		]);

		$statusCode = $response->getStatusCode();

		$data = json_decode($response->getBody(), true);

		if ($statusCode == 200) {
			return array(
				'url' => $this->clientUrl,
				'token' => $data['access_token']
			);
		} else {
			dd('huh', $data, $client);
		}

	}

	private function login() {
		
		$response = $this->client->post('/oauth/token', [
			'headers' => $this->headers,
			'http_errors' => false,
			'json' => $this->clientData
		]);
		
		$statusCode = $response->getStatusCode();
		
		$data = json_decode($response->getBody(), true);

		if ($statusCode == 200) {

			return $data['access_token']; //refresh_token

		}

		return false;

	}

	private function valueOrNull($key) {

		if(array_key_exists($key, $this->parseData)) {

			if(in_array($key, $this->booleanFields)) {

				return $this->parseData[$key] ? true : false;

			} else {

				return $this->parseData[$key];

			}

		}

		return null;

	}

}