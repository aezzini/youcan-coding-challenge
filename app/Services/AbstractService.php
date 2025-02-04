<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class AbstractService implements IService
{
    /**
     * @var $repository
     */
    protected $repository;

    /**
     * Delete by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $object = $this->repository->delete($id);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException("Unable to delete data", 406);
        }

        DB::commit();

        return $object;
    }

    /**
     * Get all.
     *
     * @return mixed
     */
    public function getAll($data = [])
    {
        return $this->repository->getAll($data);
    }

    /**
     * Get by id.
     *
     * @param $id
     * @return midex
     */
    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    /**
     * Update data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return mixed
     */
    public function update($data, $id)
    {
        $this->validate($data);

        DB::beginTransaction();

        try {
            $object = $this->repository->update($data, $id);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException("Unable to update data", 406);
        }

        DB::commit();

        return $object;
    }

    /**
     * Validate data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return mixed
     */
    public function store($data)
    {
        $this->validate($data);
        $object = $this->repository->store($data);

        return $object;
    }

    /**
     * Validate data.
     *
     * @param array $data
     */
    public function validate($data)
    {
        // Data validation goes here
    }
}
