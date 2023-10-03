<?php

namespace store\model;
/**
 * ADT for user.
 *
 * @author ivandmn
 */
class User
{

    public function __construct(
        private ?int    $id = 0,
        private ?string $username = null,
        private ?string $password = null,
        private ?string $role = null,
        private ?string $name = null,
        private ?string $surname = null
    )
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function __toString(): string
    {
        $result = "User{";
        $result .= sprintf("[id=%s]", $this->id);
        $result .= sprintf("[username=%s]", $this->username);
        $result .= sprintf("[password=%s]", $this->password);
        $result .= sprintf("[role=%s]", $this->role);
        $result .= sprintf("[name=%s]", $this->name);
        $result .= sprintf("[surname=%s]", $this->surname);
        $result .= "}";
        return $result;
    }

}