
            <div class="table table-striped table-hover">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Sno.</th>
                    <th scope="col">Contact No</th>
                    <th scope="col">Call Script</th>
                    <th scope="col">Dated</th>
                  </tr>
                </thead>
                <tbody>
                <?php $i=0; ?>
                @if(isset($leads))
                    @forelse($leads as $lead)

                    <?php $i++; ?>
                    <tr>
                      <td scope="col">{{ $i }}.</td>
                      <td scope="col">{{ $lead->mobileNo }}</td>
                      <td scope="col">{{ $lead->callScript }}</td>
                      <td scope="col">{{ $lead->created_at }}</td>
                    </tr>
                    @empty
                    <tr>
                      <td scope="col" colspan="4">No Record Found !</td>
                    </tr>
                    @endforelse
                  @else
                  <tr>
                    <td scope="col" colspan="4">No Record Found !</td>
                  </tr>
                  @endif
                </tbody>
              </table>
            </div>
            {{ $leads->links() }}