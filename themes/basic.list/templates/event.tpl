[arlo_template_region_selector]
[arlo_timezones wrap="<div class='arlo-timezone-toggle'>%s</div>"]
[arlo_event_template_summary wrap="<p>%s</p>"]
[arlo_event_template_advertised_duration wrap="<p>%s</p>"]
[arlo_event_template_tags]

<ul class="arlo-list arlo-show-more events" data-show="3" data-show-text="Show more">
	[arlo_event_list]
		[arlo_event_list_item show="3"]
		<li class="arlo-cf">
			<div class="arlo-left arlo-cal">
				[arlo_event_start_date format="M" wrap='<span class="arlo-cal-month">%s</span>']
				[arlo_event_start_date format="d" wrap='<span class="arlo-cal-day">%s</span>']
			</div>
			<div class="arlo-left arlo-event-details">
				<span class="arlo-event-time">[arlo_event_start_date format="%a %I:%M %p"] - [arlo_event_end_date format="%a %I:%M %p"]</span>
				[arlo_event_location label="Location: " wrap="<span class='arlo-event-location'>%s</span>"]
				[arlo_event_provider label="Provider: "]
				[arlo_event_delivery label="Delivery: " ]
				[arlo_event_session_description wrap='<span class="arlo-event-session-description">%s</span>']
				[arlo_event_presenters label="Presenters: "]
				[arlo_event_credits]
				[arlo_event_offers]
			</div>
			<div class="arlo-right">
				[arlo_event_registration]
				[arlo_event_session_list_item]
					
				<div class="arlo_session">
					<h6>[arlo_event_name]</h6>
					<div>[arlo_event_start_date format="%a %d %b %H:%M"] - [arlo_event_end_date format="%a %d %b %H:%M"]</div>
					[arlo_event_location]
				</div>
				[/arlo_event_session_list_item]	
			</div>
			[arlo_event_tags layout="list"]
		</li>
		[/arlo_event_list_item]
	[/arlo_event_list]


	[arlo_oa_list]
		[arlo_oa_list_item]
		<li class="arlo-cf arlo-onlineactivity">
			<div class="arlo-left arlo-cal">[arlo_oa_reference_term]</div>
			
			<div class="arlo-left">
				[arlo_oa_code] - [arlo_oa_name]
				[arlo_oa_credits]
				
				[arlo_oa_delivery_description label="Delivery: " wrap='<div class="arlo-delivery-desc">%s</div>']
				[arlo_oa_offers]
			</div>

			<div class="arlo-right">
				[arlo_oa_registration]
			</div>
		</li>
		[/arlo_oa_list_item]
	[/arlo_oa_list]
</ul>

[arlo_event_template_register_interest]
[arlo_suggest_datelocation wrap="<div class'arlo-suggest'>%s</div>"]

[arlo_content_field_item]
	[arlo_content_field_name wrap='<h5>%s</h5>']
	[arlo_content_field_text wrap='<p>%s</p>']
[/arlo_content_field_item]

<h3>Similar courses</h3>
<table class="arlo_suggest_templates">
	[arlo_suggest_templates limit="4"]
	<tr>
		<td>
			[arlo_event_template_permalink wrap='<a href="%s">'][arlo_event_template_code] - [arlo_event_template_name]</a>
		</td>
		<td align="right">[arlo_event_next_running]</td>
	</tr>
	[/arlo_suggest_templates]
</table>