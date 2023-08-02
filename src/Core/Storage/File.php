<?php

namespace Snake\Core\Storage;

use Snake\Core\Http\Request;
use Snake\Core\Support\Hash;

class File {
  private $request;

  private $file;

  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  public function getFromRequest(string $name): void
  {
    $this->file = (object) $this->request->get($name);
  }

  public function getClientOriginalName(): string
  {
    return pathinfo($this->file->name, PATHINFO_FILENAME);
  }

  public function getClientOriginalExtension(): string
  {
    return pathinfo($this->file->name, PATHINFO_EXTENSION);
  }

  public function exists(): bool
  {
    return $this->file->error === 0;
  }

  public function extension(): string
  {
    return $this->file->type;
  }

  public function hashName(): string
  {
    return Hash::unique();
  }

  public function store(string $name, string $folder = 'shared'): bool
  {
    return move_uploaded_file(
      $this->file->tmp_name,
      basepath() . '/' . $folder . '/' . $name . '.' . $this->getClientOriginalExtension()
    );
  }
}
