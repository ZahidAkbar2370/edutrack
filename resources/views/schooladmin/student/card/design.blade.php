{{-- Single student ID card — portrait, diamond photo (front only) --}}
<div class="id-card-university">
    <div class="id-hole"></div>

    <div class="id-header-uni">
        <!-- <span class="id-logo-text">LOGO</span> -->
        <div class="id-school-title">{{ strtoupper($schoolName) }}</div>
    </div>

    <div class="id-photo-zone">
        <!-- <div class="id-stripe-left" aria-hidden="true"></div> -->
        <!-- <div class="id-diamond"> -->
            <img src="{{ asset($photoUrl) }}" alt="" style="object-fit: cover;">
        <!-- </div> -->
        <!-- <div class="id-stripe-right" aria-hidden="true"></div> -->
    </div>

    <div class="id-student-block mt-3">
        <h2 class="id-student-name">{{ strtoupper($studentName) }}</h2>
        <!-- <p class="id-course-line">{{ $className }} @if($sectionName !== 'N/A') — {{ $sectionName }} @endif</p> -->

        <div class="id-fields mt-4">
            <div><span class="id-label">Roll #</span> : {{ $cardId }}</div>
            <div><span class="id-label">Father Name</span> : {{ $fatherName }}</div>
            <div><span class="id-label">Gender</span> : {{ ucfirst($studentGender) }}</div>
            <div><span class="id-label">Class</span> : {{ str_replace('Class ', '', $className) }}</div>
            <div><span class="id-label">Section</span> : {{ $sectionName }}</div>
        </div>
    </div>

    <div class="id-footer-uni">
        <!-- <div class="id-corner-bl" aria-hidden="true"></div> -->
        <!-- <div class="id-corner-br" aria-hidden="true"></div> -->
        <div class="id-signatures">
            <div class="id-sig-block">
                <!-- <div class="id-sig-script">{{ $studentName }}</div>
                <div class="id-sig-label">Signature of student</div> -->
            </div>
            <div class="id-sig-block">
                <div class="id-sig-script">{{ $principalName }}</div>
                <div class="id-sig-label">Signature of Head</div>
            </div>
        </div>
    </div>
</div>
