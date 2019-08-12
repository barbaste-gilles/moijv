<?php

namespace App\Security\Voter;

use App\Entity\Product;
use App\Repository\BorrowingRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CanBorrowVoter extends Voter
{
    /**
     * @var BorrowingRepository
     */
    private $borrowingRepository;

    /**
     * CanBorrowVoter constructor.
     * @param BorrowingRepository $borrowingRepository
     */
    public function __construct(BorrowingRepository $borrowingRepository)
    {
        $this->borrowingRepository = $borrowingRepository;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['CAN_BORROW'])
            && $subject instanceof Product;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // attribute contient le nom du ROLE (en l'occurrence 'CAN_BORROW')
        // et subject contient le produit à vérifier

        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'CAN_BORROW':
                $currentBorrowing = $this->borrowingRepository->findOneBy([
                    'dateEnd' => null,
                    'product' => $subject
                ]);
                if ($currentBorrowing) {
                    return false;
                } else {
                    return true;
                }
                break;
        }

        return false;
    }
}
