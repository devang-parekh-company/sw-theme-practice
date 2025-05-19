<?php declare(strict_types=1);

namespace SchoolWiseCommerceControls\Core\Content\SchoolLicenceInformation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @package core
 * @method void                add(SchoolLicencesInformationEntity $entity)
 * @method void                set(string $key, SchoolLicencesInformationEntity $entity)
 * @method SchoolLicencesInformationEntity[]    getIterator()
 * @method SchoolLicencesInformationEntity[]    getElements()
 * @method SchoolLicencesInformationEntity|null get(string $key)
 * @method SchoolLicencesInformationEntity|null first()
 * @method SchoolLicencesInformationEntity|null last()
 */
class SchoolLicencesInformationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return SchoolLicencesInformationEntity::class;
    }
}