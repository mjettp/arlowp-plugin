<div class="arlo" id="arlo">

[arlo_categories wrap='<div class="arlo-categories">%s</div>']
[arlo_template_region_selector]
[arlo_schedule_filters buttonclass="arlo-button"]
[arlo_category_header wrap='<div class="arlo-category-header">%s</div>']

<ul class="arlo-list schedule">
    [arlo_event_template_list_item group="category" limit="10"]
        [arlo_group_divider wrap='<li class="arlo-cf arlo-group-divider"><h3>%s</h3></li>']

        <li class='arlo-cf arlo-schedule-event'>
          <div class="arlo-schedule-column arlo-event-details">
            [arlo_event_template_permalink wrap='
            <a href="%s" class="arlo-template-name">
                ']<h5>[arlo_event_template_name]</h5>
            </a>
             [arlo_event_template_advertised_duration wrap='<div class="arlo-advertised-duration arlo-background-color1">%s</div>']
          </div>
          <div class="arlo-schedule-column arlo-price">
             [arlo_event_price wrap="<div class='arlo-offers'><i class='icons8-price-tag-filled'></i> %s</div>"]
          </div>
          <div class="arlo-schedule-column arlo-next-running">
             [arlo_event_next_running]
          </div>
        </li>
    [/arlo_event_template_list_item]
</ul>

[arlo_category_footer wrap='<div class="arlo-category-footer">%s</div>']
[arlo_no_event_text]
[arlo_schedule_pagination limit="10" wrap='<div class="arlo-pagination">%s</div>']
[arlo_powered_by]
</div>