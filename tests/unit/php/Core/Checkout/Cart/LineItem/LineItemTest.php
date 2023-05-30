<?php declare(strict_types=1);

namespace Shopware\Tests\Unit\Core\Checkout\Cart\LineItem;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Cart\CartException;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItem\LineItemCollection;
use Shopware\Core\Framework\Log\Package;

/**
 * @covers \Shopware\Core\Checkout\Cart\LineItem\LineItem
 *
 * @internal
 */
#[Package('checkout')]
class LineItemTest extends TestCase
{
    /**
     * @throws CartException
     */
    public function testCreateLineItem(): void
    {
        $lineItem = new LineItem('A', 'type');

        static::assertSame('A', $lineItem->getId());
        static::assertSame('type', $lineItem->getType());
        static::assertSame(1, $lineItem->getQuantity());
    }

    /**
     * @throws CartException
     */
    public function testCreateLineItemWithInvalidQuantity(): void
    {
        $this->expectException(CartException::class);

        new LineItem('A', 'type', null, -1);
    }

    /**
     * @throws CartException
     */
    public function testChangeLineItemToInvalidQuantity(): void
    {
        $this->expectException(CartException::class);

        $lineItem = new LineItem('A', 'type');
        $lineItem->setQuantity(0);
    }

    /**
     * @throws CartException
     */
    public function testChangeLineItemQuantity(): void
    {
        $lineItem = new LineItem('A', 'type');
        $lineItem->setStackable(true);
        $lineItem->setQuantity(5);
        static::assertSame(5, $lineItem->getQuantity());
    }

    /**
     * @throws CartException
     */
    public function testChangeNonStackableLineItemQuantity(): void
    {
        $this->expectException(CartException::class);

        $lineItem = new LineItem('A', 'type');
        $lineItem->setStackable(false);
        $lineItem->setQuantity(5);
        static::assertSame(1, $lineItem->getQuantity());
    }

    /**
     * @throws CartException
     */
    public function testChangeQuantityOfParentLineItem(): void
    {
        $lineItem = (new LineItem('A', 'type'))->setStackable(true);

        $child1 = (new LineItem('A.1', 'child', null, 3))->setStackable(true);
        $child2 = (new LineItem('A.2', 'child', null, 2))->setStackable(true);
        $child3 = (new LineItem('A.3', 'child'))->setStackable(true);

        $child4 = (new LineItem('A.3.1', 'child', null, 5))->setStackable(true);
        $child5 = (new LineItem('A.3.2', 'child', null, 10))->setStackable(true);

        $child3->setChildren(new LineItemCollection([$child4, $child5]));

        $lineItem->setChildren(new LineItemCollection([$child1, $child2, $child3]));

        $lineItem->setQuantity(2);

        static::assertSame(2, $lineItem->getQuantity());
        static::assertSame(6, $child1->getQuantity());
        static::assertSame(4, $child2->getQuantity());
        static::assertSame(2, $child3->getQuantity());
        static::assertSame(10, $child4->getQuantity());
        static::assertSame(20, $child5->getQuantity());

        $lineItem->setQuantity(3);

        static::assertSame(3, $lineItem->getQuantity());
        static::assertSame(9, $child1->getQuantity());
        static::assertSame(6, $child2->getQuantity());
        static::assertSame(3, $child3->getQuantity());
        static::assertSame(15, $child4->getQuantity());
        static::assertSame(30, $child5->getQuantity());

        $lineItem->setQuantity(1);

        static::assertSame(1, $lineItem->getQuantity());
        static::assertSame(3, $child1->getQuantity());
        static::assertSame(2, $child2->getQuantity());
        static::assertSame(1, $child3->getQuantity());
        static::assertSame(5, $child4->getQuantity());
        static::assertSame(10, $child5->getQuantity());
    }

    /**
     * @throws CartException
     */
    public function testChangeQuantityOfParentLineItemWithNonStackableChildren(): void
    {
        $lineItem = new LineItem('A', 'type');

        $child1 = new LineItem('A.1', 'child', null, 3);
        $child2 = new LineItem('A.2', 'child', null, 2);
        $child2->setStackable(false);
        $child3 = new LineItem('A.3', 'child');
        $child3->setStackable(false);

        $child4 = new LineItem('A.3.1', 'child', null, 5);
        $child5 = new LineItem('A.3.2', 'child', null, 10);

        $child3->setChildren(new LineItemCollection([$child4, $child5]));

        $lineItem->setChildren(new LineItemCollection([$child1, $child2, $child3]));

        $this->expectException(CartException::class);

        $lineItem->setQuantity(2);

        static::assertSame(2, $lineItem->getQuantity());
        static::assertSame(6, $child1->getQuantity());
        static::assertSame(2, $child2->getQuantity());
        static::assertSame(1, $child3->getQuantity());
        static::assertSame(5, $child4->getQuantity());
        static::assertSame(10, $child5->getQuantity());

        $lineItem->setQuantity(3);

        static::assertSame(3, $lineItem->getQuantity());
        static::assertSame(9, $child1->getQuantity());
        static::assertSame(2, $child2->getQuantity());
        static::assertSame(1, $child3->getQuantity());
        static::assertSame(5, $child4->getQuantity());
        static::assertSame(10, $child5->getQuantity());

        $lineItem->setQuantity(1);

        static::assertSame(1, $lineItem->getQuantity());
        static::assertSame(3, $child1->getQuantity());
        static::assertSame(2, $child2->getQuantity());
        static::assertSame(1, $child3->getQuantity());
        static::assertSame(5, $child4->getQuantity());
        static::assertSame(10, $child5->getQuantity());
    }

    /**
     * @throws CartException
     */
    public function testAddChildrenToLineItemWithInvalidQuantity(): void
    {
        $lineItem = new LineItem('A', 'type', null, 15);

        $child1 = new LineItem('A.1', 'child', null, 3);
        $child2 = new LineItem('A.2', 'child', null, 2);
        $child3 = new LineItem('A.3', 'child');

        $this->expectException(CartException::class);

        $lineItem->addChild($child1);
        $lineItem->addChild($child2);
        $lineItem->addChild($child3);
    }

    /**
     * @throws CartException
     */
    public function testSetChildrenToLineItemWithInvalidQuantity(): void
    {
        $lineItem = new LineItem('A', 'type', null, 15);

        $child1 = new LineItem('A.1', 'child', null, 3);
        $child2 = new LineItem('A.2', 'child', null, 2);
        $child3 = new LineItem('A.3', 'child');

        $this->expectException(CartException::class);

        $lineItem->setChildren(new LineItemCollection([$child1, $child2, $child3]));
    }

    /**
     * @throws CartException
     */
    public function testAddChildToLineItemWithQuantity1(): void
    {
        $lineItem = new LineItem('abc', 'type', null, 5);

        $child = new LineItem('123', 'child');

        $lineItem->addChild($child);

        $childTest = $lineItem->getChildren()->first();
        static::assertNotNull($childTest);
        static::assertSame(1, $childTest->getQuantity());
        static::assertSame(5, $lineItem->getQuantity());
    }

    /**
     * @throws CartException
     */
    public function testAddChildToLineItemWithQuantity1AndParentStackable(): void
    {
        $lineItem = new LineItem('abc', 'type', null, 5);
        $lineItem->setStackable(true);

        $child = new LineItem('123', 'child');

        $this->expectException(CartException::class);

        $lineItem->addChild($child);
    }

    public function testLineItemGetAndSetPayloadValue(): void
    {
        $lineItem = new LineItem('abc', 'type', null, 5);
        $lineItem->setPayloadValue('test', 2);

        static::assertEquals(2, $lineItem->getPayloadValue('test'));
    }
}
