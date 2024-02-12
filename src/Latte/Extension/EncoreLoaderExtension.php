<?php

declare(strict_types=1);

namespace vavo\EncoreLoader\Latte\Extension;

use Generator;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\AuxiliaryNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Extension;
use stdClass;

final class EncoreLoaderExtension extends Extension
{
    /**
     * @return array<string, (callable(Tag, TemplateParser): (Node|Generator|void)|stdClass)>
     */
    public function getTags(): array
    {
        return [
            'asset' => fn (Tag $tag): Node => $this->createAsset($tag),
        ];
    }

    public function createAsset(Tag $tag): AuxiliaryNode
    {
        $tag->parser->parseUnquotedStringOrExpression();
        $tag->parser->stream->tryConsume(',');
        $arrayNode = $tag->parser->parseArguments();

        return new AuxiliaryNode(
            static fn (PrintContext $context): string => $context->format('echo %escape(%modify($this->global->encoreLoaderService->getAsset(%node)));', $arrayNode)
        );
    }
}
