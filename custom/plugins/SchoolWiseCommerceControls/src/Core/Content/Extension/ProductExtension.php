<?php

namespace SchoolWiseCommerceControls\Core\Content\Extension;

use SchoolWiseCommerceControls\Core\Content\SchoolLicenceInformation\SchoolLicencesInformationDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ProductExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            new OneToOneAssociationField(
                'productId',
                'id',
                'product_id',
                SchoolLicencesInformationDefinition::class,
                false
            )
        );
    }

    public function getDefinitionClass(): string
    {
        return ProductDefinition::class;
    }
}