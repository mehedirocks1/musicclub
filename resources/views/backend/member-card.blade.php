<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Membership Card</title>

    <style>
        /* RESET */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: "Open Sans", sans-serif;
            font-size: 14px;
            background: #fff;
            text-align: center; 
            padding-top: 50px;
        }

        /* CONTAINER */
        .page-wrapper {
            width: 100%;
            white-space: nowrap; 
            text-align: center;
        }

        /* CARD STYLING */
        .card {
            display: inline-block; 
            vertical-align: middle;
            width: 320px;
            height: 512px;
            position: relative;

            background-size: contain !important;
            background-repeat: no-repeat;
            background-position: center;

            margin: 0 10px; 
            margin-top: 50px;
            white-space: normal;
            
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);

            /* High Resolution Print Rendering */
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            image-rendering: high-quality;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* --- INTERNAL ELEMENTS --- */
        .photo-box {
            position: absolute;
            top: 185px;
            left: 50%;
            transform: translateX(-50%);
            width: 130px;
            height: 150px;
            border-radius: 10px;
            z-index: 100;
            background-color: #d1d5db;
            border: 2px solid #fff;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .name {
            position: absolute;
            top: 365px;
            width: 100%;
            text-align: center;
            text-transform: capitalize;
            font-weight: 800;
            font-size: 18px;
            color: #000;
            z-index: 101;
        }

        .member-no-container {
            position: absolute;
            top: 395px;
            width: 100%;
            text-align: center;
            z-index: 101;
        }

        .member-no {
            display: inline-block;
            background-color: #0c8542;
            color: #fff;
            padding: 4px 15px;
            border-radius: 15px;
            font-size: 14px;
            font-weight: 700;
        }

        .blood-group {
            position: absolute;
            top: 430px;
            width: 100%;
            text-align: center;
            font-size: 15px;
            font-weight: 700;
            color: #292727ff;
            z-index: 101;
        }

        /* --- PRINT SETTINGS --- */
        @media print {
            @page {
                size: landscape;
                margin: 0;
                scale: 1 !important;
            }

            body {
                background: none;
                padding: 0;
                margin: 0;
                height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center; 
            }

            .page-wrapper {
                transform: scale(0.9); 
                transform-origin: center;
                width: 100%;
            }

            .card {
                box-shadow: none;
                border: 1px solid #ccc;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>

@php
    function base64_if_exists($path) {
        if ($path && file_exists($path)) {
            try { return base64_encode(file_get_contents($path)); } 
            catch (\Throwable $e) { return null; }
        }
        return null;
    }

    $frontPath = public_path('idcard/POJF.png');
    $backPath  = public_path('idcard/POJB.png');

    $frontBase = file_exists($frontPath) ? base64_if_exists($frontPath) : null;
    $backBase  = file_exists($backPath) ? base64_if_exists($backPath) : null;

    $userPhotoPath = null;
    if (!empty($allData->profile_pic) && file_exists(public_path($allData->profile_pic))) {
        $userPhotoPath = public_path($allData->profile_pic);
    }
    elseif (!empty($allData->profile_pic) && file_exists(public_path('upload/user_images/'.$allData->profile_pic))) {
        $userPhotoPath = public_path('upload/user_images/'.$allData->profile_pic);
    }
    elseif (!empty($allData->profile_pic) && file_exists(public_path('storage/'.$allData->profile_pic))) {
        $userPhotoPath = public_path('storage/'.$allData->profile_pic);
    }
    elseif (file_exists(public_path('upload/default.png'))) {
        $userPhotoPath = public_path('upload/default.png');
    }

    $userBase = ($userPhotoPath) ? base64_if_exists($userPhotoPath) : null;

    $displayName = trim($allData->full_name ?? ($allData->name_bn ?? 'Name'));
    $memberId    = $allData->member_id ?? ($allData->id ?? '000');
    $bloodGroup  = $allData->blood_group ?? null;
@endphp

<div class="page-wrapper">

    <div class="card front" style="background-image: url('{{ $frontBase ? 'data:image/png;base64,'.$frontBase : '' }}');">
        <div class="photo-box">
            @if($userBase)
                <img src="data:image/png;base64,{{ $userBase }}" alt="User">
            @else
                <span style="color:#666; font-size:10px;">No Photo</span>
            @endif
        </div>
        <div class="name">{{ $displayName }}</div>
        <div class="member-no-container">
            <span class="member-no">Member ID: {{ $memberId }}</span>
        </div>
        @if($bloodGroup)
            <div class="blood-group">BG: {{ $bloodGroup }}</div>
        @endif
    </div>

    <div class="card back" style="background-image: url('{{ $backBase ? 'data:image/png;base64,'.$backBase : '' }}');">
    </div>

</div>

</body>
</html>
