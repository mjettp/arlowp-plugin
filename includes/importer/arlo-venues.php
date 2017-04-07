<?php

namespace Arlo\Importer;

use Arlo\Logger;

class Venues extends BaseImporter {

	public function __construct($importer, $dbl, $message_handler, $data, $iteration = 0, $api_client = null, $file_handler = null, $scheduler = null) {
		parent::__construct($importer, $dbl, $message_handler, $data, $iteration, $api_client, $file_handler, $scheduler);

		$this->table_name = $this->dbl->prefix . 'arlo_venues';
	}

	protected function save_entity($item) {
		$slug = sanitize_title($item->VenueID . ' ' . $item->Name);
		$query = $this->dbl->query( $this->dbl->prepare( 
			"INSERT INTO " . $this->table_name . " 
			(v_arlo_id, v_name, v_geodatapointlatitude, v_geodatapointlongitude, v_physicaladdressline1, v_physicaladdressline2, v_physicaladdressline3, v_physicaladdressline4, v_physicaladdresssuburb, v_physicaladdresscity, v_physicaladdressstate, v_physicaladdresspostcode, v_physicaladdresscountry, v_viewuri, v_facilityinfodirections, v_facilityinfoparking, v_post_name, import_id) 
			VALUES ( %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )
			", 
			$item->VenueID,
			$item->Name,
			@$item->GeoData->PointLatitude,
			@$item->GeoData->PointLongitude,
			@$item->PhysicalAddress->StreetLine1,
			@$item->PhysicalAddress->StreetLine2,
			@$item->PhysicalAddress->StreetLine3,
			@$item->PhysicalAddress->StreetLine4,
			@$item->PhysicalAddress->Suburb,
			@$item->PhysicalAddress->City,
			@$item->PhysicalAddress->State,
			@$item->PhysicalAddress->PostCode,
			@$item->PhysicalAddress->Country,
			@$item->ViewUri,
			@$item->FacilityInfo->Directions->Text,
			@$item->FacilityInfo->Parking->Text,
			$slug,
			$this->import_id
		) );
						
		if ($query === false) {
			throw new \Exception('SQL error: ' . $this->dbl->last_error . ' ' .$this->dbl->last_query);
		}
						
		// create associated custom post, if it dosen't exist
		// should be arlo_venues
		if(!arlo_get_post_by_name($slug, 'arlo_venue')) {
			wp_insert_post(array(
				'post_title'    => $item->Name,
				'post_content'  => '',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_type'		=> 'arlo_venue',
				'post_name'		=> $slug
			));
		}
	}
}