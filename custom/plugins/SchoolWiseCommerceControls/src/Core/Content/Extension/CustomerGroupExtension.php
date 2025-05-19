<?php

namespace SchoolWiseCommerceControls\Core\Content\Extension;

use SchoolWiseCommerceControls\Core\Content\SchoolLicenceInformation\SchoolLicencesInformationDefinition;
use Shopware\Core\Checkout\Customer\Aggregate\CustomerGroup\CustomerGroupDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CustomerGroupExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            new OneToOneAssociationField(
                'customerGroupId',
                'id',
                'customer_group_id',
                SchoolLicencesInformationDefinition::class,
                false
            )
        );
    }

    public function getDefinitionClass(): string
    {
        return CustomerGroupDefinition::class;
    }
}