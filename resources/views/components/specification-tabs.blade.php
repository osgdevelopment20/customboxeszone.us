<div>
    <section class="mb-50">
        <div class="container">
            <div class="tab-style3">
                <ul class="nav nav-tabs text-uppercase">
                    <!--<li class="nav-item">-->
                    <!--    <a title="Specifications" class="nav-link active" id="Additional-info-tab" data-bs-toggle="tab"-->
                    <!--       href="#Additional-info">Specifications</a>-->
                    <!--</li>-->
                    <li class="nav-item">
                        <a title="Description" class="nav-link active " id="Description-tab" data-bs-toggle="tab"
                           href="#Description">Description</a>
                    </li>

                    <li class="nav-item">
                        <a title="FAQs" class="nav-link" id="Reviews-tab" data-bs-toggle="tab" href="#Faqs">FAQs</a>
                    </li>
                </ul>
                <div class="tab-content shop_info_tab entry-main-content">
                    <div class="tab-pane fade" id="Additional-info">
                        <table class="table table-striped table-hover ">
                            <tbody>
                            <tr>
                                <th>Box Style</th>
                                <td>As Mention Above</td>
                            </tr>
                            <tr>
                                <th>Dimension (L + W + H)</th>
                                <td>All Custom Sizes & Shapes</td>
                            </tr>
                            <tr>
                                <th>Minimum Run
                                    ( For Boxes and Mylar Bags)</th>
                                <td>100 Boxes - 1000 Mylar Bags ( 500 each design)</td>
                            </tr>
                            <tr>
                                <th>Paper Stock</th>
                                <td>10pt to 28pt (60lb to 400lb) Eco-Friendly Kraft, E-flute Corrugated, Bux Board, Cardstock</td>
                            </tr>
                            <tr>
                                <th>Printing</th>
                                <td>No Printing, CMYK, CMYK + 1 PMS color, CMYK + 2 PMS colors</td>
                            </tr>
                            <tr>
                                <th>Finishing</th>
                                <td>Gloss Lamination, Matte Lamination, Gloss AQ, Gloss UV, Matte UV, Spot UV, Embossing, Foiling</td>
                            </tr>
                            <tr>
                                <th>Included Options</th>
                                <td>Die Cutting, Gluing, Scored,Perforation</td>
                            </tr>
                            <tr>
                                <th>Additional Options</th>
                                <td>
                                    Eco-Friendly, Recycled Boxes, Biodegradable</td>
                            </tr>
                            <tr>
                                <th>Proof</th>
                                <td>Flat View, 3D Mock-up, Physical Sampling (On request)</td>
                            </tr>
                            <tr>
                                <th>Turnaround</th>
                                <td>
                                    7 - 8 Business Days</td>
                            </tr>
                            <tr>
                                <th>Shipping</th>
                                <td>FLAT Position</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade description  show active" id="Description">
                        <div class="container after-p test" style=" overflow-y: auto; max-height: 350px;">
                            @if(isset($description))
                                {!! $description !!}
                            @else
                            {!! sys_settings('homepage_content') !!}
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane fade" id="Faqs">
                        @php
                            $faqs = [
                                [
                                    'question' => 'How to Calculate or Measure the Sizes Boxes?',
                                    'answer' => 'To measure the size of your Boxes, follow these steps: Diameter: Measure the circular width of the box from one edge to the opposite edge. Height: Measure from the bottom of the box to the top.'
                                ],
                                [
                                    'question' => 'What is Your Standard Turnaround Time?',
                                    'answer' => 'Our standard production turnaround time is 8 to 12 business days after final design approval. This includes printing, die-cutting, and finishing processes. Shipping times may vary based on your location.'
                                ],
                                [
                                    'question' => 'What is Your Urgent Turnaround Time?',
                                    'answer' => 'For urgent orders, we offer rush production with a 4 to 6 business days turnaround. If you need expedited delivery, let us know at the time of ordering, and we’ll prioritize your request.'
                                ],
                                [
                                    'question' => 'How Many Colors Can I Print on My Boxes?',
                                    'answer' => 'We offer full-color CMYK and PMS printing, meaning you can print as many colors as you like without limitations. Whether you want a single-color minimalist look or a vibrant, multi-color design, we have advanced printing technology to bring your vision to life.'
                                ],
                                [
                                    'question' => 'What is the Maximum Order You Can Process Per Month?',
                                    'answer' => 'We can handle bulk orders efficiently, processing up to 500,000+ Boxes per month. Whether you need a small batch or a large-scale production run, we have the capacity and resources to meet your demands.'
                                ],
                                [
                                    'question' => 'Where is Your Packaging Manufactured?',
                                    'answer' => 'Our Boxes are proudly manufactured in our state-of-the-art production facility, located in the USA, UK and Middle East.'
                                ],
                                [
                                    'question' => 'How Can I Order a Physical Sample Before Placing a Mass Order?',
                                    'answer' => 'We offer physical samples for bulk orders so you can check the quality, size, and design before placing a bulk order.'
                                ],
                                [
                                    'question' => 'How Does Your Design Team Help with My Project?',
                                    'answer' => 'Our expert in-house design team provides 100% free design support to bring your packaging ideas to life.'
                                ],
                                [
                                    'question' => 'How Can I Review the Box Before It Is Printed?',
                                    'answer' => 'Before printing, we provide a 3D digital proof (mockup) or a physical sample (upon request). This ensures you can review every detail—colors, design alignment, and overall look—before final production. We only proceed once you approve the design.'
                                ]
                            ];
                        @endphp
                        @if(isset($faqData))
                            <div class="faq-content mb-20 container">
                                <div class="accordion" id="faqAccordion">
                                    @php
                                        // Decode JSON string if it's not already an array/object
                                   if (is_string($faqData)) {
                                  $faqData = json_decode($faqData, true);
                                     }

                                           $i =1;
                                    @endphp
                                    @foreach($faqData as $key => $faq)
                                        <div class="accordion-item">
                                            <h4 class="accordion-header" id="heading{{ $key }}">
                                                <button class="accordion-button {{ $key === 0 ? '' : 'collapsed' }}" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse{{ $key }}"
                                                        aria-expanded="{{ $key === 0 ? 'true' : 'false' }}"
                                                        aria-controls="collapse{{ $key }}">
                                                    Q{{$i++}}. {{ $faq['question'] }}
                                                </button>
                                            </h4>
                                            <div id="collapse{{ $key }}" class="accordion-collapse collapse {{ $key === 0 ? 'show' : '' }}"
                                                 aria-labelledby="heading{{ $key }}"
                                                 data-bs-parent="#faqAccordion">
                                                <div class="accordion-body">
                                                    {{ $faq['answer'] }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif(!empty($faqs))
                            <div class="faq-content mb-20 container">
                                <div class="accordion" id="faqAccordion">
                                    @php $i = 1; @endphp
                                    @foreach($faqs as $index => $faq)
                                        @php
                                            $isFirst = $loop->first;
                                            $headingId = 'heading' . $index;
                                            $collapseId = 'collapse' . $index;
                                        @endphp
                                        <div class="accordion-item">
                                            <h4 class="accordion-header" id="{{ $headingId }}">
                                                <button class="accordion-button {{ !$isFirst ? 'collapsed' : '' }}" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#{{ $collapseId }}"
                                                        aria-expanded="{{ $isFirst ? 'true' : 'false' }}"
                                                        aria-controls="{{ $collapseId }}">
                                                    Q{{ $i++ }}. {{ $faq['question'] }}
                                                </button>
                                            </h4>
                                            <div id="{{ $collapseId }}"
                                                 class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}"
                                                 aria-labelledby="{{ $headingId }}"
                                                 data-bs-parent="#faqAccordion">
                                                <div class="accordion-body">
                                                    {!! nl2br(e($faq['answer'])) !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
