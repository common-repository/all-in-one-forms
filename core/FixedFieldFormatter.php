<?php

use rednaoeasycalculationforms\Utilities\Sanitizer;


add_filter('allinoneforms_format_fixed_field',
    /**
     * @param $entry \rednaoeasycalculationforms\core\Managers\EntrySaver\AIOEntry
     */
    function ($content, $entry,$id, $options) {
    switch ($id)
    {
        case 'OriginURL':
            return $entry->GetMeta('origin_url');
        case 'EntryTotal':
            $form=AllInOneForms()->Form()->Get(Sanitizer::GetStringValueFromPath($entry,['FormId']),false,'form');
            if($form==null)
                return '';

            $total=Sanitizer::SanitizeNumber(Sanitizer::GetStringValueFromPath($entry,['Total']));
            return $form->FormatCurrency($total);
        case 'EntryNumber':
            return Sanitizer::GetStringValueFromPath($entry,['FormattedSequence']);
        case 'SubmissionDate':
            $date=get_option('date_format', 'F j, Y');
            $unix= Sanitizer::GetStringValueFromPath($entry,['CreationDate']);
            if($unix=='')
                return '';

            $unix=strtotime($unix);
            if($unix===false)
                return '';

            return date($date,intval($unix));

        case 'CurrentDate':
            $date=get_option('date_format', 'F j, Y');
            return date($date);
        case 'FullUserName':
        case 'FirstName':
        case 'LastName':
        case 'Email':
        case 'UserMeta':
        case 'UserRole':
            $user = get_user_by('id', Sanitizer::GetValueFromPath($entry, ['UserId']));
            if (!$user)
                return '';

            switch ($id) {
                case 'FullUserName':
                    return $user->user_firstname . ' ' . $user->user_lastname;
                case 'FirstName':
                    return $user->first_name;
                case 'LastName':
                    return $user->last_name;
                case 'Email':
                    return $user->user_email;
                case 'UserMeta':
                    if(is_string($options))
                        $options=json_decode($options,true);
                    $id=Sanitizer::GetValueFromPath($options,['Id']);
                    return get_user_meta($user->ID,$id,true);
                case 'UserRole':
                    return implode(', ', $user->roles);
            }

    }

    return $content;
},10, 4);