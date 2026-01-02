/**
 * PAXdesign Booking System JavaScript
 * Version: 2.2.0
 * Minimal, Professional, No Animations
 */

(function($) {
    'use strict';
    
    let bookingData = {
        member: null,
        service: null,
        date: null,
        time: null,
        currentStep: 1
    };
    
    let currentMonth = new Date();
    let selectedDate = null;
    
    $(document).ready(function() {
        initBookingSystem();
    });
    
    function initBookingSystem() {
        // Toggle chat widget
        $(document).on('click', '.paxdesign-booking-button', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('.paxdesign-booking-widget').toggleClass('active');
            // Render calendar when widget opens for the first time
            if ($('.paxdesign-booking-widget').hasClass('active') && $('#paxdesignCalendarDays').children().length === 0) {
                renderCalendar();
            }
        });
        
        // Close widget
        $(document).on('click', '.paxdesign-booking-close', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeDialog();
        });
        
        // Team selection
        $(document).on('click', '.paxdesign-booking-team-card', function(e) {
            e.preventDefault();
            const member = $(this).data('member');
            const hasServices = $(this).data('has-services');
            selectTeamMember(member, hasServices);
        });
        
        // Service selection
        $(document).on('click', '.paxdesign-booking-service-card', function(e) {
            e.preventDefault();
            const service = $(this).data('service');
            selectService(service);
        });
        
        // Navigation
        $(document).on('click', '.paxdesign-booking-btn-next', function(e) {
            e.preventDefault();
            nextStep();
        });
        
        $(document).on('click', '.paxdesign-booking-btn-back', function(e) {
            e.preventDefault();
            previousStep();
        });
        
        $(document).on('click', '.paxdesign-booking-btn-submit', function(e) {
            e.preventDefault();
            submitBooking();
        });
        
        // Calendar navigation
        $(document).on('click', '.paxdesign-booking-calendar-nav.prev', function(e) {
            e.preventDefault();
            previousMonth();
        });
        
        $(document).on('click', '.paxdesign-booking-calendar-nav.next', function(e) {
            e.preventDefault();
            nextMonth();
        });
        
        // Prevent widget clicks from closing it
        $(document).on('click', '.paxdesign-booking-widget', function(e) {
            e.stopPropagation();
        });
    }
    
    function closeDialog() {
        $('.paxdesign-booking-widget').removeClass('active');
        
        setTimeout(function() {
            bookingData = {
                member: null,
                service: null,
                date: null,
                time: null,
                currentStep: 1
            };
            selectedDate = null;
            
            $('.paxdesign-booking-content').removeClass('active');
            $('.paxdesign-booking-content[data-step="1"]').addClass('active');
            $('.paxdesign-booking-success').removeClass('active');
            $('.paxdesign-booking-team-card').removeClass('selected');
            $('.paxdesign-booking-service-card').removeClass('selected');
            
            updateStepIndicator();
        }, 100);
    }
    
    function selectTeamMember(member, hasServices) {
        bookingData.member = member;
        
        $('.paxdesign-booking-team-card').removeClass('selected');
        $('.paxdesign-booking-team-card[data-member="' + member + '"]').addClass('selected');
        
        const memberData = paxdesignBooking.teamMembers[member];
        $('#paxdesignSelectedMemberName').text(memberData.name);
        
        setTimeout(function() {
            if (hasServices === true || hasServices === 'true') {
                // Show service selection for Adam
                renderServices();
                $('.paxdesign-booking-content[data-step="1"]').removeClass('active');
                $('.paxdesign-booking-content[data-step="1.5"]').addClass('active');
            } else {
                // Skip to date selection for others
                nextStep();
            }
        }, 200);
    }
    
    function renderServices() {
        const $servicesGrid = $('#paxdesignServicesGrid');
        $servicesGrid.empty();
        
        const services = paxdesignBooking.services;
        
        $.each(services, function(key, service) {
            const badge = service.popular ? '<span class="service-badge popular">Beliebt</span>' : 
                         service.premium ? '<span class="service-badge premium">Premium</span>' : '';
            
            const $card = $('<div class="paxdesign-booking-service-card" data-service="' + key + '">' +
                '<div class="service-icon">' + service.icon + '</div>' +
                '<h4>' + service.name + '</h4>' +
                '<p class="service-price">ab €' + service.price_monthly + '</p>' +
                '<p class="service-description">' + service.description + '</p>' +
                badge +
                '</div>');
            
            $servicesGrid.append($card);
        });
    }
    
    function selectService(service) {
        bookingData.service = service;
        
        $('.paxdesign-booking-service-card').removeClass('selected');
        $('.paxdesign-booking-service-card[data-service="' + service + '"]').addClass('selected');
        
        const serviceData = paxdesignBooking.services[service];
        
        setTimeout(function() {
            $('.paxdesign-booking-content[data-step="1.5"]').removeClass('active');
            bookingData.currentStep = 2;
            $('.paxdesign-booking-content[data-step="2"]').addClass('active');
            updateStepIndicator();
            renderCalendar();
        }, 200);
    }
    
    function updateStepIndicator() {
        $('.paxdesign-booking-step-dot').each(function(index) {
            if (index + 1 <= bookingData.currentStep) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
        });
    }
    
    function nextStep() {
        if (bookingData.currentStep < 3) {
            $('.paxdesign-booking-content[data-step="' + bookingData.currentStep + '"]').removeClass('active');
            bookingData.currentStep++;
            $('.paxdesign-booking-content[data-step="' + bookingData.currentStep + '"]').addClass('active');
            updateStepIndicator();
            
            if (bookingData.currentStep === 2) {
                renderCalendar();
            }
            
            if (bookingData.currentStep === 3) {
                updateSummary();
            }
        }
    }
    
    function previousStep() {
        if (bookingData.currentStep > 1) {
            $('.paxdesign-booking-content[data-step="' + bookingData.currentStep + '"]').removeClass('active');
            bookingData.currentStep--;
            $('.paxdesign-booking-content[data-step="' + bookingData.currentStep + '"]').addClass('active');
            updateStepIndicator();
        }
    }
    
    // Calendar functions
    function renderCalendar() {
        const year = currentMonth.getFullYear();
        const month = currentMonth.getMonth();
        
        const monthNames = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 
                           'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
        
        $('#paxdesignCalendarTitle').text(monthNames[month] + ' ' + year);
        
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const prevLastDay = new Date(year, month, 0);
        
        const firstDayIndex = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1;
        const lastDayDate = lastDay.getDate();
        const prevLastDayDate = prevLastDay.getDate();
        
        const $daysContainer = $('#paxdesignCalendarDays');
        $daysContainer.empty();
        
        // Previous month days
        for (let i = firstDayIndex; i > 0; i--) {
            $daysContainer.append('<div class="paxdesign-booking-day other-month">' + (prevLastDayDate - i + 1) + '</div>');
        }
        
        // Current month days
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        for (let i = 1; i <= lastDayDate; i++) {
            const dayDate = new Date(year, month, i);
            dayDate.setHours(0, 0, 0, 0);
            
            const $day = $('<div class="paxdesign-booking-day">' + i + '</div>');
            
            // Disable past dates and weekends
            if (dayDate < today || dayDate.getDay() === 0 || dayDate.getDay() === 6) {
                $day.addClass('disabled');
            } else {
                $day.on('click', function() {
                    selectDate(dayDate);
                });
            }
            
            if (selectedDate && 
                dayDate.getDate() === selectedDate.getDate() &&
                dayDate.getMonth() === selectedDate.getMonth() &&
                dayDate.getFullYear() === selectedDate.getFullYear()) {
                $day.addClass('selected');
            }
            
            $daysContainer.append($day);
        }
        
        // Next month days
        const totalDays = $daysContainer.children().length;
        const remainingDays = 42 - totalDays;
        for (let i = 1; i <= remainingDays; i++) {
            $daysContainer.append('<div class="paxdesign-booking-day other-month">' + i + '</div>');
        }
    }
    
    function previousMonth() {
        currentMonth.setMonth(currentMonth.getMonth() - 1);
        renderCalendar();
    }
    
    function nextMonth() {
        currentMonth.setMonth(currentMonth.getMonth() + 1);
        renderCalendar();
    }
    
    function selectDate(date) {
        try {
            selectedDate = date;
            bookingData.date = formatDateISO(date);
            
            renderCalendar();
            renderTimeslots();
            
            const weekdays = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
            const months = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 
                           'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
            
            const dateStr = weekdays[date.getDay()] + ', ' + date.getDate() + '. ' + 
                           months[date.getMonth()] + ' ' + date.getFullYear();
            
            $('#paxdesignSelectedDateDisplay').text(dateStr);
        } catch (error) {
            console.error('Error selecting date:', error);
        }
    }
    
    function renderTimeslots() {
        const $timeslotsGrid = $('#paxdesignTimeslotsGrid');
        $timeslotsGrid.empty();
        
        const slots = [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '13:00', '13:30', '14:00', '14:30', '15:00', '15:30',
            '16:00', '16:30', '17:00'
        ];
        
        slots.forEach(function(time) {
            const $slot = $('<div class="paxdesign-booking-timeslot">' + time + '</div>');
            
            $slot.on('click', function() {
                selectTime(time);
            });
            
            if (bookingData.time === time) {
                $slot.addClass('selected');
            }
            
            $timeslotsGrid.append($slot);
        });
    }
    
    function selectTime(time) {
        bookingData.time = time;
        renderTimeslots();
        $('#paxdesignNextToDetailsBtn').prop('disabled', false);
    }
    
    function updateSummary() {
        const member = paxdesignBooking.teamMembers[bookingData.member];
        $('#paxdesignSummaryMember').text(member.name + ' - ' + member.role);
        
        // Show service if selected
        if (bookingData.service) {
            const service = paxdesignBooking.services[bookingData.service];
            $('#paxdesignSummaryService').text(service.name + ' - ab €' + service.price_monthly);
            $('#paxdesignSummaryServiceItem').show();
        } else {
            $('#paxdesignSummaryServiceItem').hide();
        }
        
        const date = new Date(bookingData.date);
        const weekdays = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
        const months = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 
                       'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
        
        const dateStr = weekdays[date.getDay()] + ', ' + date.getDate() + '. ' + 
                       months[date.getMonth()] + ' ' + date.getFullYear() + ' um ' + bookingData.time + ' Uhr';
        
        $('#paxdesignSummaryDateTime').text(dateStr);
    }
    
    function submitBooking() {
        try {
            const form = $('#paxdesignBookingDetailsForm')[0];
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            const formData = {
                member: bookingData.member,
                service: bookingData.service || '',
                date: bookingData.date,
                time: bookingData.time,
                name: $('#paxdesignBookingName').val(),
                email: $('#paxdesignBookingEmail').val(),
                phone: $('#paxdesignBookingPhone').val(),
                purpose: $('#paxdesignBookingPurpose').val(),
                message: $('#paxdesignBookingMessage').val(),
                nonce: paxdesignBooking.nonce
            };
            
            const $submitBtn = $('.paxdesign-booking-btn-submit');
            $submitBtn.text('Wird gesendet...').prop('disabled', true);
            
            $.ajax({
                url: paxdesignBooking.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'paxdesign_submit_booking',
                    member: formData.member,
                    service: formData.service,
                    date: formData.date,
                    time: formData.time,
                    name: formData.name,
                    email: formData.email,
                    phone: formData.phone,
                    purpose: formData.purpose,
                    message: formData.message,
                    nonce: formData.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $('.paxdesign-booking-content[data-step="3"]').removeClass('active');
                        
                        const member = response.data.member_info;
                        const date = new Date(formData.date);
                        const weekdays = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
                        const months = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 
                                       'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
                        
                        const dateStr = weekdays[date.getDay()] + ', ' + date.getDate() + '. ' + 
                                       months[date.getMonth()] + ' ' + date.getFullYear() + ' um ' + formData.time + ' Uhr';
                        
                        const successHTML = '<div class="paxdesign-booking-summary-item">' +
                            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">' +
                            '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>' +
                            '<circle cx="12" cy="7" r="4"/>' +
                            '</svg>' +
                            '<div>' +
                            '<span class="paxdesign-booking-summary-label">Ansprechpartner</span>' +
                            '<span class="paxdesign-booking-summary-value">' + member.name + '</span>' +
                            '</div>' +
                            '</div>' +
                            '<div class="paxdesign-booking-summary-item">' +
                            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">' +
                            '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>' +
                            '<line x1="16" y1="2" x2="16" y2="6"/>' +
                            '<line x1="8" y1="2" x2="8" y2="6"/>' +
                            '<line x1="3" y1="10" x2="21" y2="10"/>' +
                            '</svg>' +
                            '<div>' +
                            '<span class="paxdesign-booking-summary-label">Termin</span>' +
                            '<span class="paxdesign-booking-summary-value">' + dateStr + '</span>' +
                            '</div>' +
                            '</div>';
                        
                        $('#paxdesignSuccessDetails').html(successHTML);
                        $('.paxdesign-booking-success').addClass('active');
                    } else {
                        alert('Fehler: ' + response.data.message);
                        $submitBtn.text('Termin buchen').prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                    alert('Fehler beim Senden. Bitte versuchen Sie es erneut.');
                    $submitBtn.text('Termin buchen').prop('disabled', false);
                }
            });
        } catch (error) {
            console.error('Error submitting booking:', error);
            alert('Ein Fehler ist aufgetreten. Bitte versuchen Sie es erneut.');
        }
    }
    
    function formatDateISO(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return year + '-' + month + '-' + day;
    }
    
    // Initialize clock animation
    (function initClock() {
        const d = new Date();
        const convertedSeconds = ((d.getSeconds() + d.getMilliseconds() / 1000) / 60) * 360;
        const convertedMinutes = (d.getMinutes() / 60) * 360;
        const convertedHours = ((d.getHours() + d.getMinutes() / 60) / 12) * 360;
        
        const rotateSecondsTo = convertedSeconds + 360;
        const rotateMinutesTo = convertedMinutes + 360;
        const rotateHoursTo = convertedHours + 360;
        
        const root = document.documentElement;
        root.style.setProperty('--s-rotate-from', convertedSeconds + 'deg');
        root.style.setProperty('--m-rotate-from', convertedMinutes + 'deg');
        root.style.setProperty('--h-rotate-from', convertedHours + 'deg');
        root.style.setProperty('--s-rotate-to', rotateSecondsTo + 'deg');
        root.style.setProperty('--m-rotate-to', rotateMinutesTo + 'deg');
        root.style.setProperty('--h-rotate-to', rotateHoursTo + 'deg');
    })();
    
})(jQuery);
