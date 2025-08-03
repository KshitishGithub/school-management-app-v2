@extends('layouts.master')
@section('content')
@push('title')
<title>Pass out Students</title>
@endpush
@php
define('PAGE', 'passout');
@endphp
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Students</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Pass out students</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="student-group-form">
            <form action="{{ route('passout') }}" method="get">
                <div class="row">
                    <div class="col-lg-2 col-md-6">
                        <div class="form-group">
                            <select class="form-control" name="class" id="selectedClass"
                                aria-label="Default select example">
                                <option selected value="">Select class</option>
                                @if ($classes->isNotEmpty())
                                @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @if (Request::has('class') &&
                                    Request::get('class')==$class->id) selected @endif>
                                    {{ $class->class }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="search-student-btn">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="search-student-btn">
                            <a href="{{ route('passout') }}" class="btn btn-primary">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table border-1 star-student table-hover table-center mb-0 table-striped mb-2">
                                <thead class="student-thread">
                                    <tr>
                                        <th width='5%'>SL</th>
                                        <th>Registration ID</th>
                                        <th>Name</th>
                                        <th>Total Marks</th>
                                        <th>Roll No</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $key => $student)
                                    <tr>
                                        <td>{{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration
                                            }}</td>
                                        <td>{{ config('website.registration') . $student->id }}</td>
                                        <td>
                                            <h2 class="table-avatar">
                                                <span class="avatar avatar-sm me-2">
                                                    <img class="avatar-img rounded-squre"
                                                        src="{{ asset('uploads/images/registration/' . $student->photo) }}"
                                                        alt="User Image">
                                                </span>
                                                <a>{{ $student->name }}</a>
                                            </h2>
                                        </td>
                                        <td>{{ $student->total_marks }}</td>
                                        <td>{{ $student->roll_no }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-success passOut text-light"
                                                data-id="{{ $student->id }}">Pass Out</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $students->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('customJS')
    <script>
        $(document).ready(function () {
            // Pass out the students
            $(document).on("click", ".passOut", function (e) {
                e.preventDefault();
                var student_id = $(this).data("id");

                $.ajax({
                    url: "{{ route('getClass') }}",
                    method: 'get',
                    data: {
                        student_id: student_id,
                    },
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Processing...',
                            text: 'Fetching class details...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function (response) {
                        Swal.close();

                        // Second SweetAlert for class selection
                        Swal.fire({
                            title: 'Select a class',
                            html: `
                            <select id="classDropdown" class="swal2-input form-control">
                                <option selected disabled value="">Select class</option>
                            </select>
                            `,
                            focusConfirm: false,
                            showCancelButton: true,
                            confirmButtonText: 'Submit',
                            didOpen: () => {
                                // Populate the class dropdown with data from the response
                                const classDropdown = document.getElementById('classDropdown');
                                response.forEach(classItem => {
                                    const option = document.createElement('option');
                                    option.value = classItem.id;
                                    option.textContent = classItem.class;
                                    classDropdown.appendChild(option);
                                });
                            },
                            preConfirm: () => {
                                const selectedClass = document.getElementById('classDropdown').value;
                                if (!selectedClass) {
                                    Swal.showValidationMessage('Please select a class!');
                                    return false;
                                }
                                return selectedClass;
                            }
                        }).then((classResult) => {
                            if (classResult.isConfirmed) {
                                // Second AJAX call to process pass-out
                                $.ajax({
                                    url: "{{ route('passout.student') }}",
                                    method: 'post',
                                    data: {
                                        class: classResult.value,
                                        student_id: student_id,
                                    },
                                    beforeSend: function () {
                                        Swal.fire({
                                            title: 'Processing...',
                                            text: 'Passing out the student...',
                                            allowOutsideClick: false,
                                            didOpen: () => {
                                                Swal.showLoading();
                                            }
                                        });
                                    },
                                    success: function (response) {
                                        Swal.close();
                                        console.log(response);
                                        if(response.status){
                                            Swal.fire({
                                                title: 'Success!',
                                                text: 'Student has been passed out successfully, and roll no is - ' + response.data.roll_no,
                                                icon: 'success'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    location.reload();
                                                }
                                            });
                                        }else{
                                            Swal.fire({
                                                title: 'Error!',
                                                text: response.message,
                                                icon: 'error'
                                            });
                                        }
                                    },
                                    error: function (xhr, status, error) {
                                        Swal.close();
                                        Swal.fire({
                                            title: 'Error!',
                                            text: response.message,
                                            icon: 'error'
                                        });
                                        console.error(xhr.responseText);
                                    }
                                });
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.close();
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to fetch class details. Please try again.',
                            icon: 'error'
                        });
                        console.error(xhr.responseText);
                    }
                });
            });
        });

    </script>
@endsection
