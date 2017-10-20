<?php declare(strict_types=1);

namespace Rector\NodeAnalyzer\Contrib;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\PrettyPrinter\Standard;
use Rector\Node\Attribute;

final class ControllerMethodAnalyzer
{
    /**
     * @var Standard
     */
    private $standardPrinter;

    public function __construct(Standard $standardPrinter)
    {
        $this->standardPrinter = $standardPrinter;
    }

    /**
     * Detect if is <some>Action() in Controller
     */
    public function isAction(Node $node): bool
    {
        if (! $node instanceof ClassMethod) {
            return false;
        }

        $parentClassName = $node->getAttribute(Attribute::PARENT_CLASS_NAME);
        $controllerClass = 'Symfony\Bundle\FrameworkBundle\Controller\Controller';

        if ($parentClassName !== $controllerClass) {
            return false;
        }

        return Strings::endsWith($node->name->toString(), 'Action');
    }

    public function doesNodeContain(ClassMethod $classMethodNode, string $part): bool
    {
        $methodInString = $this->standardPrinter->prettyPrint([$classMethodNode]);

        return Strings::contains($methodInString, $part);
    }
}
