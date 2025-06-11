 <div class="p-4">
     <table class="min-w-full border border-zinc-200 dark:border-zinc-700">
         <thead class="bg-zinc-100 dark:bg-zinc-800">
             <tr>
                 <th class="p-2">Name</th>
                 <th class="p-2">Email</th>
                 <th class="p-2">Role</th>
                 <th class="p-2">Change Role</th>
             </tr>
         </thead>
         <tbody>
             @foreach ($users as $u)
                 <tr class="border-t dark:border-zinc-700">
                     <td class="p-2">{{ $u->name }}</td>
                     <td class="p-2">{{ $u->email }}</td>
                     <td class="p-2">{{ ucfirst($u->role) }}</td>
                     <td class="p-2">
                         <select wire:change="updateRole({{ $u->id }}, $event.target.value)"
                             class="border rounded p-1">
                             <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin</option>
                             <option value="pharmacist" {{ $u->role === 'pharmacist' ? 'selected' : '' }}>Pharmacist
                             </option>
                             <option value="cashier" {{ $u->role === 'cashier' ? 'selected' : '' }}>Cashier</option>
                         </select>
                     </td>
                 </tr>
             @endforeach
         </tbody>
     </table>

     @if (session()->has('success'))
         <div class="mt-4 text-green-600">{{ session('success') }}</div>
     @endif
 </div>
