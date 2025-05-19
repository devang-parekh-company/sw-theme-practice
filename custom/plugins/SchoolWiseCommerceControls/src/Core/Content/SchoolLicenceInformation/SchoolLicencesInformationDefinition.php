<?php declare(strict_types=1);

namespace SchoolWiseCommerceControls\Core\Content\SchoolLicenceInformation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Checkout\Customer\Aggregate\CustomerGroup\CustomerGroupDefinition;
use Shopware\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;

class SchoolLicencesInformationDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'school_licences_information';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return SchoolLicencesInformationCollection::class;
    }

    public function getEntityClass(): string
    {
        return SchoolLicencesInformationEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),

            (new FkField('customer_group_id', 'customerGroupId', CustomerGroupDefinition::class))->addFlags(new Required()),
            (new FkField('category_id', 'categoryId', CategoryDefinition::class))->addFlags(new Required()),
            (new ReferenceVersionField(CategoryDefinition::class))->addFlags(new Required()),

            (new FkField('product_id', 'productId', ProductDefinition::class))->addFlags(new Required()),
            (new ReferenceVersionField(ProductDefinition::class))->addFlags(new Required()),

            new OneToOneAssociationField('customerGroup', 'customer_group_id','id', CustomerGroupDefinition::class, false),
            new OneToOneAssociationField('category', 'category_id', 'id', CategoryDefinition::class, false),
            new OneToOneAssociationField('product', 'product_id', 'id', ProductDefinition::class, false),
        ]);
    }
}
