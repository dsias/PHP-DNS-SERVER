<?php

namespace yswery\DNS;

class StackableResolver implements ResolverInterface
{

    /**
     * @var array
     */
    protected $resolvers;

    public function __construct(array $resolvers = [])
    {
        $this->resolvers = $resolvers;
    }

    /**
     * @param ResourceRecord[] $question
     * @return array
     */
    public function getAnswer(array $question): array
    {
        foreach ($this->resolvers as $resolver) {
            $answer = $resolver->getAnswer($question);
            if (!empty($answer)) {
                return $answer;
            }
        }

        return [];
    }

    /**
     * Check if any of the resolvers supports recursion
     *
     * @return boolean true if any resolver supports recursion
     */
    public function allowsRecursion(): bool
    {
        foreach ($this->resolvers as $resolver) {
            if ($resolver->allowsRecursion()) {
                return true;
            }
        }

        return false;
    }

    /*
     * Check if any resolver knows about a domain
     *
     * @param  string  $domain the domain to check for
     * @return boolean         true if some resolver holds info about $domain
     */
    public function isAuthority($domain): bool
    {
        foreach ($this->resolvers as $resolver) {
            if ($resolver->isAuthority($domain)) {
                return true;
            }
        }
        return false;
    }
}
