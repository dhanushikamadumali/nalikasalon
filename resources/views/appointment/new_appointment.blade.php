@extends('layouts.main.master')

@section('content')
<style>
  #customerDetails {
      padding: 15px;
  }

  .customer-info {
      margin-bottom: 10px;
      color: black;
      font-size: 15px;
  }

  .customer-info strong {
      display: inline-block;
      width: 100px;
      margin-right:20px;
  }

  #timeSlots {
      padding: 10px;
      border: 1px solid #ddd; 
      border-radius: 4px; 
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
      background-color: #FAF9F9; 
      display: flex; 
      flex-wrap: wrap; /* Allows items to wrap to the next line if needed */
      gap: 10px; /* Space between time slots */
  }

  .time-slot {
      padding: 10px;
      margin: 0;
      width: 100px; 
      text-align: center;
      border: 1px solid #ccc; 
      border-radius: 4px; 
      background-color: #fff; 
      cursor: pointer; 
      transition: background-color 0.3s, border-color 0.3s;
      flex: 0 1 auto; /* Flex item can grow and shrink as needed */
  }

  .time-slot:hover {
      background-color: #f0f0f0; 
      border-color: #999; 
  }

  .custom-card-bodys {
      padding: 15px;
  }

  #cusd select{
    display: flex;
    flex-direction: column;
  }

  #totalPrice {
    font-weight: bold;
    font-size:20px;
    color: #d9534f; /* Customize the color to suit your design */
  }

  #card02pre{
    margin-top:20px;
    padding:20px 20px 20px 50px;
  }
</style>

<main role="main" class="main-content">

 @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
 @endif
 @if (session('error'))
 <div class="alert alert-danger">
     {{ session('error') }}
 </div>
 @endif
  <div class="custom-card">
    <div class="custom-card-bodys">
      <ul class="custom-navs" id="myTab" role="tablist">
        <li class="custom-nav-item">
          <a class="custom-nav-link active" id="pre-tab" data-toggle="tab" href="#pre" role="tab" aria-controls="home" aria-selected="true">Pre Booking</a>
        </li>
        <li class="custom-nav-item">
          <a class="custom-nav-link" id="real-time-tab" data-toggle="tab" href="#real-time" role="tab" aria-controls="profile" aria-selected="false">Real Time Booking</a>
        </li>
      </ul>
      <div class="custom-tab-content" id="myTabContent">
        <div class="custom-tab-pane active" id="pre" role="tabpanel" aria-labelledby="pre-tab">
          <div class="container-fluids">
              <div class="row justify-content-center pl-5 pr-5">
                <div class="col-12">
                  <div class="card shadow mb-4 p-2 pl-3">
                    <div class="card-body">
                      <form action="{{ route('appointment.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group row">
                          <label for="customerSelect" class="col-sm-2 col-form-label" style="color:black;">Customer <i class="text-danger">*</i></label>
                          <div class="col-sm-8 d-flex align-items-center">
                              <select name="contact_number_1" class="form-control" id="customer_code" required>
                                  <option value="">Select Customer</option>
                                  @foreach($customers->unique('supplier_code') as $customer)
                                      <option 
                                          value="{{ $customer->contact_number_1 }}" 
                                          data-name="{{ $customer->name }}" 
                                          data-contact="{{ $customer->contact_number_1 }}" 
                                          data-address="{{ $customer->address }}" 
                                          data-dob="{{ $customer->date_of_birth }}" 
                                          data-code="{{ $customer->supplier_code }}">
                                          {{ $customer->contact_number_1 }}
                                      </option>
                                  @endforeach
                              </select>
                              <button class="btn btn-outline-secondary ml-2" type="button" id="add-customer-btn">
                                  <i class="fe fe-10 fe-plus"></i>
                              </button>
                          </div>
                        </div>

                        <!-- Customer details display section -->

                        <div id="customerDetails" class="col-sm-8 mt-4 mb-4" style="display: none;">
                            <div class="customer-info" id="customerName"></div>
                            <div class="customer-info" id="customerContact"></div>
                            <div class="customer-info" id="customerAddress"></div>
                            <div class="customer-info" id="customerDOB"></div>
                        </div>

                        <!-- Hidden input fields for storing customer details -->
                        <input type="hidden" id="hiddenCustomerName" name="customer_name">
                        <input type="hidden" id="hiddenCustomerContact" name="customer_contact">
                        <input type="hidden" id="hiddenCustomerAddress" name="customer_address">
                        <input type="hidden" id="hiddenCustomerDOB" name="customer_dob">
                        <input type="hidden" id="hiddenCustomerId" name="customer_id">


                          <!-- Service Dropdown -->
                          <div class="form-group row">
                              <label for="service-select" class="col-sm-2 col-form-label" style="color:black;">Service <i class="text-danger">*</i></label>
                              <div class="col-md-6">
                                  <select class="form-control" id="service-select" name="service_id" required>
                                      <option value="">Select Service</option>
                                      @foreach($services as $service)
                                          <option value="{{ $service->service_code }}" data-name="{{ $service->service_name }}">
                                              {{ $service->service_code }} - {{ $service->service_name }}
                                          </option>
                                      @endforeach
                                  </select>
                              </div>
                          </div>

                          <!-- Hidden input field for storing the selected service name -->
                          <input type="hidden" id="hiddenServiceName" name="service_name">


                          <!-- Package Dropdowns -->
                          <div class="form-group row">
                              <label for="package-select-1" class="col-sm-2 col-form-label" style="color:black;">Package 01 : <i class="text-danger">*</i></label>
                              <div class="col-md-6">
                                  <select class="form-control" id="package-select-1" name="package_id_1" required>
                                      <option value="">Select Package</option>
                                  </select>
                              </div>
                          </div>

                            <!-- Date Selection -->
                                    <div class="form-group row">
                                        <label for="start_date" class="col-sm-2 col-form-label" style="color:black;">Date <i class="text-danger">*</i></label>
                                        <div class="col-md-6">
                                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                                        </div>
                                    </div>



                                    <!-- Time Slot Selection -->
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" style="color:black;">Time Slots <i class="text-danger">*</i></label>
                                        <div class="col-md-6">
                                            <div id="timeSlots">
                                                <!-- Time slots will be dynamically populated here -->
                                            </div>
                                            <input type="hidden" id="appointment_time" name="appointment_time" required>
                                        </div>
                                    </div>

                                    <!-- Appointment Time Display -->
                                    <div class="form-group row">
                                        <label for="appointmentTime" class="col-sm-2 col-form-label" style="color:black;">Appointment Time <i class="text-danger">*</i></label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" id="appointmentTime" name="appointment_time" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                    <label for="main_dresser" class="col-sm-2 col-form-label" style="color:black;">Main Dresser <i class="text-danger">*</i></label>
                                    <div class="col-md-6">
                                        <select id="main_dresser" name="main_dresser" class="form-control" required>
                                            <option value="">Select Main Dresser</option>
                                            <!-- Options will be dynamically populated here -->
                                        </select>
                                    </div>
                                </div>




                            <!-- Assistant 1 Name -->
                            <div class="form-group row">
                                <label for="assistant_1" class="col-sm-2 col-form-label" style="color:black;">Assistant <i class="text-danger">*</i></label>
                                <div class="col-md-6">
                                    <select class="form-control" id="assistant_1" name="assistant_1_name">
                                        <option value="">Select Assistant</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Assistant 2 Name -->
                            <div class="form-group row">
                                <label for="assistant_2" class="col-sm-2 col-form-label" style="color:black;">Assistant <i class="text-danger"></i></label>
                                <div class="col-md-6">
                                    <select class="form-control" id="assistant_2" name="assistant_2_name">
                                        <option value="">Select Assistant</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Assistant 3 Name -->
                            <div class="form-group row">
                                <label for="assistant_3" class="col-sm-2 col-form-label" style="color:black;">Assistant <i class="text-danger"></i></label>
                                <div class="col-md-6">
                                    <select class="form-control" id="assistant_3" name="assistant_3_name">
                                        <option value="">Select Assistant</option>
                                    </select>
                                </div>
                            </div>



                          <!-- Note -->
                          <div class="form-group">
                              <label for="eventNote" class="col-form-label" style="color:black;">Note</label>
                              <textarea class="form-control" id="eventNote" name="note" placeholder="Add some note for your event"></textarea>
                          </div>


                          <div class="custom-card" id="card02pre">

                            <!-- Payment Method -->
                            <div class="form-group row">
                                <label for="paymentMethod" class="col-sm-2 col-form-label" style="color:black;">Payment Method <i class="text-danger">*</i></label>
                                <div class="col-md-6">
                                    <select class="form-control" id="paymentMethod" name="payment_method" required>
                                        <option value="">Select Payment Method</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="debit_card">Debit Card</option>
                                        <option value="paypal">PayPal</option>
                                        <option value="cash">Cash</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Advanced Payment -->
                            <div class="form-group row">
                                <label for="advancedPayment" class="col-sm-2 col-form-label" style="color:black;">Advanced Payment <i class="text-danger">*</i></label>
                                <div class="col-md-6">
                                    <input type="number" class="form-control" id="advancedPayment" name="advanced_payment" required>
                                </div>
                            </div>

                            <!-- Total Price -->
                            <div class="form-group row">
                                <label for="totalPrice" class="col-sm-2 col-form-label" style="color:black;">Total Price <i class="text-danger">*</i></label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="totalPrice" name="total_price" readonly>
                                </div>
                            </div>

                          </div>

                          <div class="form-group row">
                            <div class="col-sm-10 mt-5">
                              <button type="submit" class="btn btn-primary">Save Appointment</button>
                            </div>
                          </div>
                      </form>
                    </div>
                  </div>

                  
                </div>
              </div>
            </div>
        </div>

        <div class="custom-tab-pane" id="real-time" role="tabpanel" aria-labelledby="real-time-tab">
          <!-- Content for Real Time Booking -->
          kavidu
        </div>
      </div>
    </div>

    <!-- Add New Customer Modal -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                        <div class="modal-content">
                                        
                            <div class="modal-header">
                                <h5 class="modal-title" id="addCustomerModalLabel">Add New Customer</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                            
                                    <form action="{{ route('POS.customerstore') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="name">Name</label>
                                                    <input type="text" class="form-control" id="name" name="name"  required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="contact_number_1">Contact Number 1 : </label>
                                                    <input type="text" class="form-control" id="contact_number_1" name="contact_number_1"  required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="contact_number_2">Contact Number 2 : </label>
                                                    <input type="text" class="form-control" id="contact_number_2" name="contact_number_2" >
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="address">Address</label>
                                                    <input type="text" class="form-control" id="address" name="address"  required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="date_of_birth">Date of Birth</label>
                                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"  required>
                                                </div>


                                                <div class="text-center">
                                                <button type="submit" class="btn btn-primary float-center">Submit</button>
                                                </div>

                                            </form>
                                            
                                </div>
                                       
                        </div>
                </div>
                            
            </div>
  </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 on the select element
    $('#customer_code').select2({
        placeholder: 'Select or type customer contact number',
        allowClear: true
    });

    // Update customer details display when selection changes
    $('#customer_code').on('change', function() {
    var selectedOption = $(this).find('option:selected');
    var name = selectedOption.data('name') || 'N/A';
    var contact = selectedOption.data('contact') || 'N/A';
    var address = selectedOption.data('address') || 'N/A';
    var dob = selectedOption.data('dob') || 'N/A';
    var code = selectedOption.data('code') || 'N/A';

    if (name && contact && address && dob) {
        $('#customerDetails').show();
        $('#customerName').html('<strong>Name:</strong> ' + name);
        $('#customerContact').html('<strong>Contact:</strong> ' + contact);
        $('#customerAddress').html('<strong>Address:</strong> ' + address);
        $('#customerDOB').html('<strong>Date of Birth:</strong> ' + dob);
        
        // Set the hidden input fields
        $('#hiddenCustomerName').val(name);
        $('#hiddenCustomerContact').val(contact);
        $('#hiddenCustomerAddress').val(address);
        $('#hiddenCustomerDOB').val(dob);
        $('#hiddenCustomerId').val(code);

    } else {
        $('#customerDetails').hide();
        
        // Clear the hidden input fields
        $('#hiddenCustomerName').val('');
        $('#hiddenCustomerContact').val('');
        $('#hiddenCustomerAddress').val('');
        $('#hiddenCustomerDOB').val('');
        $('#hiddenCustomerId').val('');
    }
});

    // Show modal to add customer
    $('#add-customer-btn').on('click', function() {
        new bootstrap.Modal(document.getElementById('addCustomerModal')).show();
    });

    // Redirect to the same page on cancel
    $('#cancel-btn').on('click', function() {
        window.location.reload();
    });

    $('#service-select').on('change', function() {
    var selectedOption = $(this).find('option:selected');
    var serviceName = selectedOption.data('name') || '';

    // Set the hidden input field with the service name
    $('#hiddenServiceName').val(serviceName);
});

});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
  

    timeSlots.forEach(function(timeSlot) {
        timeSlot.addEventListener('click', function() {
            appointmentTimeInput.value = timeSlot.textContent.trim();
        });
    });

    // Tab switching functionality
    const tabs = document.querySelectorAll('.custom-nav-link');
    const tabPanes = document.querySelectorAll('.custom-tab-pane');

    tabs.forEach(tab => {
        tab.addEventListener('click', function(event) {
            event.preventDefault();
            const targetPaneId = this.getAttribute('href').substring(1);

            tabs.forEach(t => t.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));

            this.classList.add('active');
            document.getElementById(targetPaneId).classList.add('active');
        });
    });

    // Ensure the first tab is active on page load
    tabs[0].classList.add('active');
    tabPanes[0].classList.add('active');
});

var timeSlots = document.querySelectorAll('.time-slot');
    var appointmentTimeInput = document.getElementById('appointmentTime');

timeSlots.forEach(function(timeSlot) {
        timeSlot.addEventListener('click', function() {
            appointmentTimeInput.value = timeSlot.textContent.trim();
        });
    });
</script>


<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Function to update total price
    function updateTotalPrice() {
        var total = 0;
        $('#package-select-1, #package-select-2, #package-select-3').each(function() {
            var packagePrice = $(this).find('option:selected').data('price');
            if (packagePrice) {
                total += parseFloat(packagePrice);
            }
        });
        $('#totalPrice').val(total.toFixed(2));
    }

    // Event handler for package dropdown change
    $('#package-select-1, #package-select-2, #package-select-3').change(updateTotalPrice);

    // Existing service-select change event handler
    $('#service-select').change(function() {
        var serviceCode = $(this).val();

        $('#package-select-1').empty().append('<option value="">Select Package</option>');
        $('#package-select-2').empty().append('<option value="">Select Package</option>');
        $('#package-select-3').empty().append('<option value="">Select Package</option>');

        if (serviceCode) {
            $.ajax({
                url: '{{ route("getPackagesByService") }}',
                method: 'GET',
                data: { service_code: serviceCode },
                success: function(response) {
                    response.packages.forEach(function(package) {
                        var option = `<option value="${package.id}" data-price="${package.price}">${package.package_name}</option>`;
                        $('#package-select-1').append(option);
                        $('#package-select-2').append(option);
                        $('#package-select-3').append(option);
                    });
                    updateTotalPrice();
                }
            });
        }
    });
});
</script>

<script>
    // Show modal to add customer
    $('#add-customer-btn').on('click', function() {
        new bootstrap.Modal(document.getElementById('addCustomerModal')).show();
    });
</script>










<!-- JavaScript to Handle Dynamic Time Slot Population -->
<script>

    
document.getElementById('start_date').addEventListener('change', function() {
    var date = this.value;
    if (date) {
        console.log('Fetching available time slots for date:', date);
        fetchAvailableTimeSlots(date);
    }
});

document.getElementById('start_date').addEventListener('change', function() {
    var date = this.value;
    var serviceSelect = document.getElementById('service-select');
    var serviceId = serviceSelect.value;
    if (date && serviceId) {
        console.log('Fetching available time slots for date:', date, 'and service:', serviceId);
        fetchAvailableTimeSlots(date, serviceId);
    }
});

function fetchAvailableTimeSlots(date, serviceId) {
    console.log(`Fetching available time slots for date: ${date} and service: ${serviceId}`);
    fetch(`/get-available-time-slots?date=${date}&service_id=${serviceId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            console.log('Available time slots data:', data);
            var timeSlotsContainer = document.getElementById('timeSlots');
            timeSlotsContainer.innerHTML = '';

            data.available_time_slots.forEach(function(timeSlot) {
                var timeSlotDiv = document.createElement('div');
                timeSlotDiv.className = 'time-slot';
                timeSlotDiv.textContent = timeSlot;
                timeSlotDiv.addEventListener('click', function() {
                    selectTimeSlot(timeSlot, date);
                });
                timeSlotsContainer.appendChild(timeSlotDiv);
            });

            if (data.available_time_slots.length === 0) {
                console.log('No available time slots found.');
            }
        })
        .catch(error => console.error('Error fetching time slots:', error));
}

document.getElementById('service-select').addEventListener('change', function() {
    var serviceId = this.value;
    var date = document.getElementById('start_date').value;
    if (date && serviceId) {
        console.log('Fetching available time slots for date:', date, 'and service:', serviceId);
        fetchAvailableTimeSlots(date, serviceId);
    }
});


function selectTimeSlot(timeSlot, date) {
    console.log(`Selected time slot: ${timeSlot} on date: ${date}`);
    document.getElementById('appointment_time').value = timeSlot;
    document.getElementById('appointmentTime').value = timeSlot;
    var timeSlots = document.querySelectorAll('.time-slot');
    timeSlots.forEach(slot => {
        slot.style.backgroundColor = '#fff';
        slot.style.borderColor = '#ccc';
    });
    var selectedSlot = [...timeSlots].find(slot => slot.textContent === timeSlot);
    if (selectedSlot) {
        selectedSlot.style.backgroundColor = '#d0e9ff';
        selectedSlot.style.borderColor = '#4a90e2';
    }
    fetchAvailableMainDressers(date, timeSlot);
    fetchAvailableAssistants(date, timeSlot, 'assistant_1');
    fetchAvailableAssistants(date, timeSlot, 'assistant_2');
    fetchAvailableAssistants(date, timeSlot, 'assistant_3');
}

function fetchAvailableMainDressers(date, timeSlot) {
    fetch(`/get-available-main-dressers?date=${date}&time_slot=${timeSlot}`)
        .then(response => response.json())
        .then(data => {
            console.log('Available Dressers:', data.available_dressers);
            var mainDresserSelect = document.getElementById('main_dresser');
            mainDresserSelect.innerHTML = '<option value="">Select Main Dresser</option>';

            data.available_dressers.forEach(function(dresser) {
                var option = document.createElement('option');
                option.value = dresser.id;
                option.textContent = `${dresser.firstname} ${dresser.lastname}`;
                mainDresserSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching available dressers:', error));
}

function fetchAvailableAssistants(date, timeSlot, assistantSelectId) {
    fetch(`/get-available-assistants?date=${date}&time_slot=${timeSlot}`)
        .then(response => response.json())
        .then(data => {
            console.log(`Available Assistants for ${assistantSelectId}:`, data.available_assistants);
            var assistantSelect = document.getElementById(assistantSelectId);
            assistantSelect.innerHTML = '<option value="">Select Assistant</option>';

            data.available_assistants.forEach(function(assistant) {
                var option = document.createElement('option');
                option.value = assistant.id;
                option.textContent = `${assistant.firstname} ${assistant.lastname}`;
                assistantSelect.appendChild(option);
            });
        })
        .catch(error => console.error(`Error fetching available assistants for ${assistantSelectId}:`, error));
}

</script>



@endsection
