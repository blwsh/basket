# blwsh/basket

A simple basket package which demonstrates use of TDD to develop functionality using PHP.

## Requirements

* Docker

### Getting started

As long as you have docker installed, you should be able to easily run all tests using the instructions found in the 
Running Tests section of this page.

If you're having problems getting this project to run, please feel free to contact me!

### Running tests

```shell
docker-compose exec app composer test
```

### Structures

#### Basket

A `Basket` is a serializable class which contains an array of `BasketItem`s. Its primary concerns are; adding and 
removing `BasketItem`s to the basket, increasing and decreasing the quantities of `BasketItem`s and presenting general basket information such as the 
baskets total.

#### BasketItems

A `BasketItem` is a container for any class which implements the `Purchasable` contract. A `BasketItem` has a 
quantity, total and discountedTotal. The total is calculated from the `Purchasable` item price multiplied by the `BasketItem` quantity.
The discounted priceTotal is calculated by applying `DiscountPolicie`s to the `BasketItem` total.

When a `BasketItem` is added to a `Basket` via the `Basket` add method, the `BasketItem` is associated with the `Basket` via a 
one to many relationships with the `Basket` (and the inverse is true when removed).

#### Product

A class which implements `Purchasable` contract, so it can be easily added to a `Basket`. Additionally, 
the class implements `Stockable` which makes stock handling for the `Product` possible.

#### DiscountPolicy

A class which defines how a `BasketItem` should be discounted, by how much and the conditions for applying the discount
The `DiscountPolicy` conditions can be defined as an array of callbacks which are each passed the `BasketItem` to potentially apply a discount to
(Which includes a reference to the `Basket` it belongs to) that must return true or false. If all callbacks return true, the discount can be
applied to the `BasketItem`.

**Note** *In later versions you will be able to pass predefined rules such as over n amount.*

#### HasAttributes

A trait which helps mimic models defined in the MVC design pattern. While there is no storage driver/
service, it's useful to easily insert and retrieve attributes associated with an object via its magic methods.

#### HasStock

A trait which implements methods found in the `Stockable` contract in order to provide a simple interface for retrieving 
`Product` information such as stock levels, when items are stocked and when the batch of stock will expire.

#### Helpers

Util - Simple functions that often return primitive types such as string or aid in development.
