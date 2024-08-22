<?php

class Node
{
    public int|float|null $data = null;
    public ?Node $left = null;
    public ?Node $right = null;

    public function __construct(int|float $data)
    {
        $this->data = $data;
    }
}

class BinaryTree
{
    private ?Node $root = null;

    public function add(int|float $data): bool
    {
        $node = new Node($data);

        if ($this->root === null) {
            $this->root = $node;
            return true;
        }

        return $this->insertNodeIntoTree($node);
    }

    private function insertNodeIntoTree(Node $node, ?Node $parent = null): bool
    {
        if (!$parent) {
            $curParent = $this->root;
        } else {
            $curParent = $parent;
        }

        if ($node->data < $curParent->data) {
            if ($curParent->left === null) {
                $curParent->left = $node;
                return true;
            }

            return $this->insertNodeIntoTree($node, $curParent->left);
        }

        if ($node->data > $curParent->data) {
            if ($curParent->right === null) {
                $curParent->right = $node;
                return true;
            }

            return $this->insertNodeIntoTree($node, $curParent->right);
        }

        return false;
    }

    private function getParent(Node $node): ?Node
    {
        $ended = false;
        $current = $this->root;

        while (!$ended) {
            if ($node->data < $current->data) {
                if ($node->data === $current->left->data) {
                    return $current;
                }

                $current = $current->left;
                continue;
            }

            if ($node->data > $current->data) {
                if ($node->data === $current->right->data) {
                    return $current;
                }

                $current = $current->right;
                continue;
            }

            $ended = true;
        }

        return null;
    }

    /**
     * @param int|float $data
     * @return Node|null
     */
    private function getElement(int|float $data): ?Node
    {
        $current = $this->root;

        while (true) {
            if ($current->data === $data) {
                return $current;
            }

            if ($data > $current->data && $current->right) {
                $current = $current->right;
                continue;
            } elseif ($data < $current->data && $current->left) {
                $current = $current->left;
                continue;
            }

            break;
        }

        return $current;
    }

    public function remove(int|float $data): bool
    {
        if ($this->root === null) {
            return false;
        }

        $toRemove = $this->getElement($data);
        if (!$toRemove) {
            return false;
        }

        $parent = $this->getParent($toRemove);

        if ($toRemove->left === null && $toRemove->right === null) {
            if ($parent->data > $toRemove->data) {
                $parent->right = null;
            } else {
                $parent->left = null;
            }

            unset($toRemove);
            return true;
        }

        if ($this->root->data === $toRemove->data) {
            $node = $this->root->left;
            while ($node->right != null) {
                $node = $node->right;
            }

            $parentNode = $this->getParent($node);
            if ($node->left !== null) {
                $parentNode->right = $node->left;
            }

            $node->left = $this->root->left;
            $node->right = $this->root->right;
            $this->root = $node;
            return true;
        }

        if ($toRemove->left || $toRemove->right) {
            $parentNode = $this->getParent($toRemove);

            if ($toRemove->left && $toRemove->right) {
                $parentNode->right = $toRemove->right;
                $parentNode->right->left = $toRemove->left;
            } elseif ($toRemove->left && $toRemove->right === null) {
                $parentNode->left = $toRemove->left;
            } elseif ($toRemove->right && $toRemove->left === null) {
                $parentNode->right = $toRemove->right;
            }

            return true;
        }

        return false;
    }
}

$bTree = new BinaryTree();
$bTree->add(10);
$bTree->add(20);
$bTree->add(4);
$bTree->add(3);
$bTree->add(6);
$bTree->add(1);
$bTree->add(5);
$bTree->add(11);
$bTree->add(21);
$bTree->add(9);
$bTree->add(8);
$bTree->add(12);
$bTree->add(50);
//$bTree->remove(1);
$bTree->add(19);
$bTree->add(122);
$bTree->add(90);
$bTree->add(503);
$bTree->remove(10);
$bTree->add(7);
