<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Membership Card</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Open Sans", sans-serif; font-size: 12px; color: #222; }
        .wrap { width: 100%; display:flex; justify-content:center; padding: 20px 0; }
        .card { width: 333px; border-radius: 10px; overflow: hidden; margin-bottom: 18px; }
        .front, .back {
            height: 216px;
            background-size: cover;
            background-position: center;
            position: relative; /* This is crucial for absolute positioning */
        }

        /* --- FRONT CARD (Corrected Layout) --- */
        .user-photo {
            width: 80px;
            height: 90px;
            object-fit: cover;
            border-radius: 4px;
            position: absolute;
            top: 25px;
            left: 20px;
            border: 2px solid white;
            z-index: 1; /* Keep user photo below gold shape, but above background */
        }
        .logo-front {
            width: 70px;
            position: absolute;
            top: 25px;
            right: 15px;
            z-index: 3;
        }
        .shape {
            width: 65px;
            height: 216px;
            position: absolute;
            right: 82px; /* Corrected position */
            top: 0;
            z-index: 2; /* Gold shape behind text but above user photo */
        }
        .name {
            position: absolute;
            top: 135px;
            left: 20px; /* Aligned with membership number */
            text-transform: capitalize;
            font-weight: 700;
            font-size: 16px;
            color: #fff799; /* Yellow text */
            text-align: left; /* Left aligned */
            z-index: 4; /* Name above everything else */
        }
        .member-no {
            position: absolute;
            top: 165px;
            left: 20px; /* Aligned with name */
            background-color: #0c8542;
            border-radius: 20px;
            padding: 6px 15px;
            border: 1px solid rgb(233, 218, 35);
            text-align: left; /* Left aligned */
            font-size: 14px;
            color: #fff;
            z-index: 4; /* Membership No above everything else */
        }

        /* --- BACK CARD (No changes needed) --- */
        .back .info {
            position: absolute;
            width: 100%;
            top: 25px;
            padding: 0 18px;
            color: #fff;
            text-align: center;
            font-size: 10px;
        }
        .back .details {
            position: absolute;
            bottom: 20px;
            left: 18px;
            color: #fff;
            font-size: 11px;
            text-align: left;
        }
    </style>
</head>
<body>
@php
    use Carbon\Carbon;

    // helper: base64-encode file if exists, else null
    function base64_if_exists($path) {
        if ($path && file_exists($path)) {
            try {
                return base64_encode(file_get_contents($path));
            } catch (\Throwable $e) {
                return null;
            }
        }
        return null;
    }

    // card assets (public/idcard/...)
    $frontPathCandidates = [
        public_path('idcard/fornt_part_id_card.png'),
        public_path('idcard/fornt_part_id_card.jpg'),
    ];
    $backPathCandidates  = [
        public_path('idcard/back_part_id_card.png'),
        public_path('idcard/back_part_id_card.jpg'),
    ];
    $logoPathCandidates  = [
        public_path('idcard/logo.png'),
        public_path('idcard/logo.jpg'),
    ];
    $shapePathCandidates = [ // Re-added shape path
        public_path('idcard/shape.png'),
        public_path('idcard/shape.jpg'),
    ];

    $findFirst = function(array $candidates) {
        foreach ($candidates as $p) {
            if (file_exists($p)) return $p;
        }
        return null;
    };

    $frontPath = $findFirst($frontPathCandidates);
    $backPath  = $findFirst($backPathCandidates);
    $logoPath  = $findFirst($logoPathCandidates);
    $shapePath = $findFirst($shapePathCandidates); // Re-added shape path

    $frontBase = base64_if_exists($frontPath);
    $backBase  = base64_if_exists($backPath);
    $logoBase  = base64_if_exists($logoPath);
    $shapeBase = base64_if_exists($shapePath); // Re-added shape path

    // Resolve member photo from multiple likely locations:
    $photoCandidates = [];

    // If profile_pic already holds a full path or public path, check directly
    if (! empty($allData->profile_pic)) {
        $photoCandidates[] = public_path($allData->profile_pic);
        $photoCandidates[] = public_path('upload/user_images/'.$allData->profile_pic);
        $photoCandidates[] = public_path('storage/'.$allData->profile_pic);
        $photoCandidates[] = public_path('members/profile_pics/'.$allData->profile_pic);
        $photoCandidates[] = public_path('profile_pics/'.$allData->profile_pic);
    }

    // Older code might store images under upload/user_images
    if (! empty($allData->image)) {
        $photoCandidates[] = public_path('upload/user_images/'.$allData->image);
    }

    // fallback: check a few fixed paths where a default avatar might exist
    $photoCandidates[] = public_path('upload/default.png');
    $photoCandidates[] = public_path('images/default-user.png');

    $userPhotoPath = null;
    foreach ($photoCandidates as $p) {
        if ($p && file_exists($p)) {
            $userPhotoPath = $p;
            break;
        }
    }

    $userBase = base64_if_exists($userPhotoPath);

    // Friendly values for display
    $displayName = trim($allData->full_name ?? ($allData->name_bn ?? ''));
    // registration date formatting
    $registrationRaw = $allData->registration_date ?? $allData->registration_date ?? null;
    $registrationFormatted = null;
    if ($registrationRaw) {
        try {
            // Format to match the new image: 03-11-2025
            $registrationFormatted = Carbon::parse($registrationRaw)->format('d-m-Y');
        } catch (\Throwable $e) {
            $registrationFormatted = $registrationRaw;
        }
    }
@endphp

<div class="wrap">
    <div>
        {{-- FRONT --}}
        <div class="card front" style="background-image: url('{{ $frontBase ? 'data:image/png;base64,'.$frontBase : '' }}');">
            
            @if($userBase)
                <img class="user-photo" src="data:image/png;base64,{{ $userBase }}" alt="user">
            @endif

            @if($logoBase)
                <img class="logo-front" src="data:image/png;base64,{{ $logoBase }}" alt="logo">
            @endif

            {{-- Gold shape image is back --}}
            @if($shapeBase)
                <img class="shape" src="data:image/png;base64,{{ $shapeBase }}" alt="shape">
            @endif

            <div class="name">{{ $displayName }}</div>
            <div class="member-no">Membership No: {{ $allData->member_id ?? ($allData->id ?? '') }}</div>
        </div>

        {{-- SPACING --}}
        <div style="height:10px"></div>

        {{-- BACK --}}
        <div class="card back" style="background-image: url('{{ $backBase ? 'data:image/png;base64,'.$backBase : '' }}');">
            <div class="info">
                @if($logoBase)
                    <img src="data:image/png;base64,{{ $logoBase }}" style="width:50px; margin-bottom:6px;">
                @endif
                <div style="font-weight:700; color:#fff799; font-size:11px;">Prokriti O Jibon Club</div>
                <div style="font-weight:700; color:#fff; font-size:10px; margin-bottom:6px;">Head Office</div> 
                <div style="color:#fff; font-size:10px; margin-bottom:6px;">Evergreen Plaza (4th Floor), 260/B, Tejgaon I/A, Dhaka–1208</div>
                <div style="color:#fff; font-size:10px; margin-bottom:6px;">Phone: +88 01409 964 888, +88 01409 964 999</div>
                <div style="color:#fff; font-size:10px;">www.club.pojf.org | club@pojf.org</div>
            </div>

            <div class="details">
                <p>
                    <strong style="color:#fff799">District:</strong> {{ $allData->district ?? '' }}
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <strong style="color:#fff799">Blood Group:</strong> {{ $allData->blood_group ?? '' }}
                </p>
                <p>
                    <strong style="color:#fff799">Member Since:</strong> {{ $registrationFormatted ?? ($allData->registration_date ?? '') }}
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>