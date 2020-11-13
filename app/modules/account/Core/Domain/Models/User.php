<?php

namespace A7Pro\Account\Core\Domain\Models;

class User
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_REJECTED = 'rejected';

    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_CUSTOMER = 'customer';
    const ROLE_TECHNICIAN = 'technician';
    const ROLE_HEAD_OF_DPC = 'head_of_dpc';
    const ROLE_HEAD_OF_DPP = 'head_of_dpp';

    private UserId $id;
    private string $name;
    private ?string $username;
    private ?Email $email;
    private ?Phone $phone;
    private ?string $profilePict;
    private string $password;
    private string $status;
    private array $roles;
    private ?Customer $customerAttributes;
    private ?Technician $technicianAttributes;

    public function __construct(
        UserId $id,
        string $name,
        ?string $username,
        ?Email $email,
        ?Phone $phone,
        ?string $profilePict,
        string $password,
        string $status,
        array $roles,
        ?Customer $customerAttributes,
        ?Technician $technicianAttributes
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->username = $username;
        $this->email = $email;
        $this->phone = $phone;
        $this->profilePict = $profilePict;
        $this->password = $password;
        $this->status = $status;
        $this->roles = $roles;
        $this->customerAttributes = $customerAttributes;
        $this->technicianAttributes = $technicianAttributes;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function getPhone(): ?Phone
    {
        return $this->phone;
    }

    public function getProfilePict(): ?string
    {
        return $this->profilePict;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getCustomerAttributes(): ?Customer
    {
        return $this->customerAttributes;
    }

    public function getTechnicianAttributes(): ?Technician
    {
        return $this->technicianAttributes;
    }

    public function canLogin(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles);
    }

    public function canVerifyTechnician(): bool
    {
        return $this->hasRole(User::ROLE_HEAD_OF_DPC) && $this->technicianAttributes;
    }

    public function isAdministrator(): bool
    {
        return $this->hasRole(User::ROLE_SUPER_ADMIN)
            || $this->hasRole(User::ROLE_HEAD_OF_DPC)
            || $this->hasRole(User::ROLE_HEAD_OF_DPP);
    }

    public function approveAsTechnician()
    {
        $this->status = self::STATUS_ACTIVE;
    }

    public function rejectAsTechnician()
    {
        $this->status = self::STATUS_REJECTED;
    }

    public function giveCustomerRole()
    {
        if (!$this->hasRole(self::ROLE_CUSTOMER)) {
            $this->roles[] = self::ROLE_CUSTOMER;
        }

        if (!$this->customerAttributes) {
            $this->customerAttributes = new Customer(null, null);
        }
    }

    public function giveHeadOfDpcRole()
    {
        if (!in_array(self::ROLE_HEAD_OF_DPC, $this->roles)) {
            array_push($this->roles, self::ROLE_HEAD_OF_DPC);
        }

        $this->status = self::STATUS_ACTIVE;
    }

    public function giveHeadOfDppRole()
    {
        if (!in_array(self::ROLE_HEAD_OF_DPP, $this->roles)) {
            array_push($this->roles, self::ROLE_HEAD_OF_DPP);
        }

        $this->status = self::STATUS_ACTIVE;
    }

    public function verifyPassword(string $password): bool
    {
        if (strlen($password) === 0) {
            return false;
        }

        return password_verify($password, $this->password);
    }

    public function validate(): array
    {
        $errors = [];

        if ($this->email && !$this->email->isValid()) {
            $errors[] = 'invalid_email';
        }

        if ($this->phone && !$this->phone->isValid()) {
            $errors[] = 'invalid_phone';
        }

        return $errors;
    }
}