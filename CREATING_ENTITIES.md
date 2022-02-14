# Creating Entities

For now, you can use the Doctrine command line tools to build entities based on your mapping definitions.

**NOTE:** I am only using the **Static PHP mappings** and nothing else (no Annotations, xml, yaml, etc). 

Here is an Entity class template:

```
<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class MyEntity implements EntityInterface
{
    public static function loadMetadata(ClassMetadata $metadata)
    {
        // Very basic definitions
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('my_entity');
        $builder->createField('id', 'integer')->makePrimaryKey()->generatedValue()->build();

        // Contintue with mapping as per
        // https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/php-mapping.html#php-mapping

        $builder->addField('foo', 'string'); // fluent
    }
}
```

Once you have defined the mapping, you can run the command line tools. These tools will be removed for some reason Doctrine 3.
The command line tools will create getters and setters and set up all the class properties. `private $id` etc. If you have defined your associations (relationships between tables/entities), then the CLI will also attempt to create the methods to interact with those associations. 

To run the command line tools:

`./vendor/bin/doctrine orm:generate-entities src`

Because the entity class files have the `Blog\Entity` namespace, the generated files will be placed in the `src/Blog/Entity` folder. 

## Mapping associations with the builder

You can do the basic mapping with Doctrine defaults like this:


```
<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Author implements EntityInterface
{
    public static function loadMetadata(ClassMetadata $metadata)
    {
        // ...
        // One author has many articles
        $builder->addOneToMany('articles', Article::class, 'author');
        // ...
    }
}
```

The 3rd parameter is the "mappedBy" parameter and is required on a oneToMany. It refers to the name of the association (relationship) 
on the other side. For example:


```
<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Article implements EntityInterface
{
    public static function loadMetadata(ClassMetadata $metadata)
    {
        // ...
        // Each article, of which there are many, has only one author (belongs to)
        $builder->addManyToOne('author', Author::class, 'articles');
        // ...
    }
}
```

The 3rd parameter here is called "inversedBy" and is optional


### Beyond the defaults

You can use alternative methods to create more advanced mappings where you can set parameters.

```
<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class Author implements EntityInterface
{
    public static function loadMetadata(ClassMetadata $metadata)
    {
        // ...
        // The default would be `author_id` but we override to `user_id`
        $builder->createManyToOne('author', User::class)
                ->addJoinColumn('user_id', 'id')
                ->build();
        // ...
    }
}
```
