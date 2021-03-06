<?php
namespace Ecom\EcomToolbox\Domain\Repository;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Nicolas Scheidler <Nicolas.Scheidler@ecom-ex.com>, ecom instruments GmbH
 *           Sebastian Iffland <sebastian.iffland@ecom-ex.com>, ecom instruments GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * The repository for Regions
 */
class RegionRepository extends \Ecom\EcomToolbox\Domain\Repository\AbstractRepository {

	/**
	 * @var array
	 */
	protected $defaultOrderings = [
		'title' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
	];

	/**
	 * Set repository wide settings
	 */
	public function initializeObject() {
		/** @var \TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface $querySettings */
		$querySettings = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface::class);
		$querySettings->setRespectStoragePage(false); // Disable storage pid
		$this->setDefaultQuerySettings($querySettings);
	}

	/**
	 * findByUidList - find and sort entities defined by list
	 *
	 * @param array $list
	 * @param int   $mode Current modes are 0=excludeList, default=includeList
	 *
	 * @return null|\TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function findByUidList(array $list = [ ], $mode = 1) {
		/** In order to keep orderings as set in flexForm, we fetch record by record, storing them into an ObjectStorage */
		$return = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		if ( $mode === 0 ) {
			/** @var \Ecom\EcomToolbox\Domain\Model\Region $region */
			foreach ( $this->findAll() as $region ) {
				if ( $region instanceof \Ecom\EcomToolbox\Domain\Model\Region && $region->isVerified() && !in_array($region->getUid(), $list) ) {
					$return->attach($region);
				}
			}
		} else {
			foreach ( $list as $uid ) {
				/** @var \Ecom\EcomToolbox\Domain\Model\Region $region */
				$region = $this->findByUid($uid);
				if ( $region instanceof \Ecom\EcomToolbox\Domain\Model\Region && $region->isVerified() ) {
					$return->attach($region);
				}
			}
		}

		return $return;
	}
}