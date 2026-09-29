import { Routes, Route, Link, useNavigate, Navigate } from 'react-router-dom';
import './App.css';
import { 
  Users, 
  BookOpen, 
  Building2, 
  FileText, 
  CheckCircle2, 
  LogOut, 
  Plus, 
  LayoutDashboard,
  Lock,
  UserCheck
} from 'lucide-react';

export default function App() {
  // Authentication State
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [currentUser, setCurrentUser] = useState(null);
  
  // Login Form States
  const [usernameInput, setUsernameInput] = useState('');
  const [passwordInput, setPasswordInput] = useState('');
  const [loginError, setLoginError] = useState('');

  // Active Tab View
  const [activeTab, setActiveTab] = useState('dashboard');

  // Database State (Matching Proposal ERD)
  const [departments, setDepartments] = useState([
    { id: 1, name: 'Finance & Planning', manager: 'Salum Ali' },
    { id: 2, name: 'IT & Infrastructure', manager: 'Amina Omar' },
  ]);

  const [staffList, setStaffList] = useState([
    { id: 1, full_name: 'Usama Talib Juma', department_id: 1, email: 'usama@mof.go.tz', phone: '+255 771 000 111', qualification: 'Bachelor CS' },
    { id: 2, full_name: 'Fauzia Faraj Othman', department_id: 2, email: 'fauzia@mof.go.tz', phone: '+255 772 000 222', qualification: 'Master IT' },
  ]);

  const [trainings, setTrainings] = useState([
    { id: 1, name: 'Cyber Security Essentials', age_requirement: 21, qualification: 'IT Degree', country: 'Tanzania', place: 'Zanzibar', start_time: '2026-10-01', end_time: '2026-10-15' },
    { id: 2, name: 'Public Financial Management', age_requirement: 25, qualification: 'Finance Degree', country: 'Tanzania', place: 'Pemba', start_time: '2026-11-05', end_time: '2026-11-20' },
  ]);

  const [enrollments, setEnrollments] = useState([
    { id: 1, staff_id: 1, training_id: 1, enrollment_date: '2026-09-28', status: 'approved' },
    { id: 2, staff_id: 2, training_id: 2, enrollment_date: '2026-09-29', status: 'pending' },
  ]);

  // Form States
  const [newStaff, setNewStaff] = useState({ full_name: '', department_id: 1, email: '', phone_number: '', qualification: '' });
  const [newTraining, setNewTraining] = useState({ name: '', age_requirement: 20, qualification: '', country: 'Tanzania', place: '', start_time: '', end_time: '' });

  // Handle Login
  const handleLogin = (e) => {
    e.preventDefault();
    setLoginError('');

    // Predefined Accounts for Demo
    if (usernameInput === 'admin' && passwordInput === '123') {
      setCurrentUser({ name: 'System Administrator', role: 'Admin', username: 'admin' });
      setIsAuthenticated(true);
    } else if (usernameInput === 'manager' && passwordInput === '123') {
      setCurrentUser({ name: 'Salum Ali (Dept Manager)', role: 'Manager', username: 'manager' });
      setIsAuthenticated(true);
    } else if (usernameInput === 'staff' && passwordInput === '123') {
      setCurrentUser({ name: 'Usama Talib Juma', role: 'Staff', username: 'staff' });
      setIsAuthenticated(true);
    } else {
      setLoginError('Invalid Username or Password. Please try again.');
    }
  };

  // Handle Logout
  const handleLogout = () => {
    setIsAuthenticated(false);
    setCurrentUser(null);
    setUsernameInput('');
    setPasswordInput('');
  };

  // Handlers for Forms
  const handleAddStaff = (e) => {
    e.preventDefault();
    setStaffList([...staffList, { ...newStaff, id: Date.now() }]);
    setNewStaff({ full_name: '', department_id: 1, email: '', phone_number: '', qualification: '' });
  };

  const handleAddTraining = (e) => {
    e.preventDefault();
    setTrainings([...trainings, { ...newTraining, id: Date.now() }]);
    setNewTraining({ name: '', age_requirement: 20, qualification: '', country: 'Tanzania', place: '', start_time: '', end_time: '' });
  };

  const handleEnrollStaff = (staffId, trainingId) => {
    const exists = enrollments.some(e => e.staff_id === staffId && e.training_id === trainingId);
    if (exists) {
      alert("Staff is already registered for this training program!");
      return;
    }
    setEnrollments([...enrollments, { id: Date.now(), staff_id: staffId, training_id: trainingId, enrollment_date: new Date().toISOString().split('T')[0], status: 'pending' }]);
    alert("Application submitted successfully!");
  };

  const handleApproveEnrollment = (id) => {
    setEnrollments(enrollments.map(e => e.id === id ? { ...e, status: 'approved' } : e));
  };

  // 1. LOGIN SCREEN RENDER
  if (!isAuthenticated) {
    return (
      <div className="login-screen">
        <div className="login-card">
          <div className="login-header">
            <BookOpen size={48} color="#0284c7" />
            <h2>TMS Zanzibar 2026</h2>
            <p>Ministry of Finance and Planning</p>
          </div>

          {loginError && <div className="error-alert">{loginError}</div>}

          <form onSubmit={handleLogin}>
            <div className="form-group" style={{ marginBottom: '16px' }}>
              <label>Username</label>
              <input 
                type="text" 
                required 
                placeholder="Enter username" 
                value={usernameInput} 
                onChange={(e) => setUsernameInput(e.target.value)} 
              />
            </div>

            <div className="form-group" style={{ marginBottom: '24px' }}>
              <label>Password</label>
              <input 
                type="password" 
                required 
                placeholder="Enter password" 
                value={passwordInput} 
                onChange={(e) => setPasswordInput(e.target.value)} 
              />
            </div>

            <button type="submit" className="btn btn-primary btn-full">
              <Lock size={18} /> Secure Login
            </button>
          </form>

          <div className="demo-credentials">
            <strong>Demo Login Accounts (Password: 123)</strong>
            <ul style={{ paddingLeft: '18px', marginTop: '6px' }}>
              <li><strong>Admin:</strong> admin</li>
              <li><strong>Manager:</strong> manager</li>
              <li><strong>Staff:</strong> staff</li>
            </ul>
          </div>
        </div>
      </div>
    );
  }

  // 2. MAIN DASHBOARD RENDER (When Logged In)
  return (
    <div className="app-container">
      {/* Sidebar Navigation */}
      <div className="sidebar">
        <div className="logo-section">
          <BookOpen size={32} color="#0284c7" />
          <h2>Training Management System</h2>
        </div>
<ul className="nav-links">
  <li className="nav-item">
    <Link to="/dashboard" style={{ color: 'inherit', textDecoration: 'none', display: 'flex', gap: '12px', alignItems: 'center' }}>
      <LayoutDashboard size={20} /> Dashboard
    </Link>
  </li>
  
  {(currentUser?.role === 'Admin' || currentUser?.role === 'Manager') && (
    <li className="nav-item">
      <Link to="/staff" style={{ color: 'inherit', textDecoration: 'none', display: 'flex', gap: '12px', alignItems: 'center' }}>
        <Users size={20} /> Staff Directory
      </Link>
    </li>
  )}

  <li className="nav-item">
    <Link to="/trainings" style={{ color: 'inherit', textDecoration: 'none', display: 'flex', gap: '12px', alignItems: 'center' }}>
      <BookOpen size={20} /> Training Programs
    </Link>
  </li>

  {currentUser?.role === 'Admin' && (
    <li className="nav-item">
      <Link to="/departments" style={{ color: 'inherit', textDecoration: 'none', display: 'flex', gap: '12px', alignItems: 'center' }}>
        <Building2 size={20} /> Departments
      </Link>
    </li>
  )}

  <li className="nav-item">
    <Link to="/reports" style={{ color: 'inherit', textDecoration: 'none', display: 'flex', gap: '12px', alignItems: 'center' }}>
      <FileText size={20} /> Training Reports
    </Link>
  </li>
</ul>


        <button className="logout-btn" onClick={handleLogout}>
          <LogOut size={20} /> Logout
        </button>
      </div>

      {/* Main Full-Width Content Panel */}
      <div className="main-content">
        <header className="header">
          <div>
            <h1>Ministry Training Portal</h1>
            <p style={{ color: '#64748b' }}>Ministry of Finance and Planning - Zanzibar</p>
          </div>
          <div className="user-profile">
            <div className="avatar">{currentUser.name[0]}</div>
            <div>
              <strong>{currentUser.name}</strong>
              <div style={{ fontSize: '0.8rem', color: '#0284c7', fontWeight: '600' }}>
                Role: {currentUser.role}
              </div>
            </div>
          </div>
        </header>

        {/* Dashboard Overview */}
        {activeTab === 'dashboard' && (
          <div>
            <div className="stats-grid">
              <div className="stat-card">
                <div>
                  <span style={{ color: '#64748b', fontSize: '0.9rem' }}>Total Staff</span>
                  <div className="stat-number">{staffList.length}</div>
                </div>
                <Users color="#0284c7" size={36} />
              </div>

              <div className="stat-card">
                <div>
                  <span style={{ color: '#64748b', fontSize: '0.9rem' }}>Active Trainings</span>
                  <div className="stat-number">{trainings.length}</div>
                </div>
                <BookOpen color="#10b981" size={36} />
              </div>

              <div className="stat-card">
                <div>
                  <span style={{ color: '#64748b', fontSize: '0.9rem' }}>Departments</span>
                  <div className="stat-number">{departments.length}</div>
                </div>
                <Building2 color="#f59e0b" size={36} />
              </div>

              <div className="stat-card">
                <div>
                  <span style={{ color: '#64748b', fontSize: '0.9rem' }}>Total Enrollments</span>
                  <div className="stat-number">{enrollments.length}</div>
                </div>
                <CheckCircle2 color="#6366f1" size={36} />
              </div>
            </div>

            <div className="section-card">
              <h3><UserCheck size={20} /> Recent Training Applications</h3>
              <div className="table-container">
                <table>
                  <thead>
                    <tr>
                      <th>Staff Member</th>
                      <th>Training Program</th>
                      <th>Application Date</th>
                      <th>Approval Status</th>
                      {currentUser.role !== 'Staff' && <th>Action</th>}
                    </tr>
                  </thead>
                  <tbody>
                    {enrollments.map((item) => {
                      const staff = staffList.find(s => s.id === item.staff_id);
                      const training = trainings.find(t => t.id === item.training_id);
                      return (
                        <tr key={item.id}>
                          <td><strong>{staff ? staff.full_name : 'Unknown'}</strong></td>
                          <td>{training ? training.name : 'Unknown Program'}</td>
                          <td>{item.enrollment_date}</td>
                          <td>
                            <span className={`status-badge ${item.status === 'approved' ? 'status-approved' : 'status-pending'}`}>
                              {item.status}
                            </span>
                          </td>
                          {currentUser.role !== 'Staff' && (
                            <td>
                              {item.status === 'pending' && (
                                <button className="btn btn-primary" style={{ padding: '6px 14px', fontSize: '0.85rem' }} onClick={() => handleApproveEnrollment(item.id)}>
                                  Approve Request
                                </button>
                              )}
                            </td>
                          )}
                        </tr>
                      );
                    })}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        )}

        {/* Staff Management */}
        {activeTab === 'staff' && (
          <div>
            {currentUser.role === 'Admin' && (
              <div className="section-card">
                <h3><Plus size={20} /> Register New Staff Member</h3>
                <form onSubmit={handleAddStaff} className="form-grid">
                  <div className="form-group">
                    <label>Full Name</label>
                    <input 
                      type="text" 
                      required 
                      placeholder="e.g. Ali Khamis"
                      value={newStaff.full_name} 
                      onChange={e => setNewStaff({...newStaff, full_name: e.target.value})} 
                    />
                  </div>
                  <div className="form-group">
                    <label>Department</label>
                    <select 
                      value={newStaff.department_id} 
                      onChange={e => setNewStaff({...newStaff, department_id: Number(e.target.value)})}
                    >
                      {departments.map(d => <option key={d.id} value={d.id}>{d.name}</option>)}
                    </select>
                  </div>
                  <div className="form-group">
                    <label>Email Address</label>
                    <input 
                      type="email" 
                      required 
                      placeholder="ali@mof.go.tz"
                      value={newStaff.email} 
                      onChange={e => setNewStaff({...newStaff, email: e.target.value})} 
                    />
                  </div>
                  <div className="form-group">
                    <label>Phone Number</label>
                    <input 
                      type="text" 
                      required 
                      placeholder="+255 77X XXX XXX"
                      value={newStaff.phone_number} 
                      onChange={e => setNewStaff({...newStaff, phone_number: e.target.value})} 
                    />
                  </div>
                  <div className="form-group">
                    <label>Highest Qualification</label>
                    <input 
                      type="text" 
                      required 
                      placeholder="e.g. Master of Science in IT"
                      value={newStaff.qualification} 
                      onChange={e => setNewStaff({...newStaff, qualification: e.target.value})} 
                    />
                  </div>
                  <div style={{ gridColumn: '1 / -1', marginTop: '10px' }}>
                    <button type="submit" className="btn btn-primary">Save Staff Member</button>
                  </div>
                </form>
              </div>
            )}

            <div className="section-card">
              <h3>Ministry Staff Directory</h3>
              <div className="table-container">
                <table>
                  <thead>
                    <tr>
                      <th>Full Name</th>
                      <th>Department</th>
                      <th>Email Address</th>
                      <th>Qualification</th>
                    </tr>
                  </thead>
                  <tbody>
                    {staffList.map((staff) => {
                      const dept = departments.find(d => d.id === staff.department_id);
                      return (
                        <tr key={staff.id}>
                          <td><strong>{staff.full_name}</strong></td>
                          <td>{dept ? dept.name : 'N/A'}</td>
                          <td>{staff.email}</td>
                          <td>{staff.qualification}</td>
                        </tr>
                      );
                    })}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        )}

        {/* Training Management */}
        {activeTab === 'trainings' && (
          <div>
            {currentUser.role === 'Admin' && (
              <div className="section-card">
                <h3><Plus size={20} /> Schedule New Training Session</h3>
                <form onSubmit={handleAddTraining} className="form-grid">
                  <div className="form-group">
                    <label>Course Title</label>
                    <input 
                      type="text" 
                      required 
                      placeholder="e.g. Advanced Data Analytics"
                      value={newTraining.name} 
                      onChange={e => setNewTraining({...newTraining, name: e.target.value})} 
                    />
                  </div>
                  <div className="form-group">
                    <label>Min. Age Requirement</label>
                    <input 
                      type="number" 
                      required 
                      value={newTraining.age_requirement} 
                      onChange={e => setNewTraining({...newTraining, age_requirement: Number(e.target.value)})} 
                    />
                  </div>
                  <div className="form-group">
                    <label>Qualification Prerequisite</label>
                    <input 
                      type="text" 
                      required 
                      placeholder="Degree / Diploma"
                      value={newTraining.qualification} 
                      onChange={e => setNewTraining({...newTraining, qualification: e.target.value})} 
                    />
                  </div>
                  <div className="form-group">
                    <label>Venue / Location</label>
                    <input 
                      type="text" 
                      required 
                      placeholder="Zanzibar / Pemba"
                      value={newTraining.place} 
                      onChange={e => setNewTraining({...newTraining, place: e.target.value})} 
                    />
                  </div>
                  <div className="form-group">
                    <label>Start Date</label>
                    <input 
                      type="date" 
                      required 
                      value={newTraining.start_time} 
                      onChange={e => setNewTraining({...newTraining, start_time: e.target.value})} 
                    />
                  </div>
                  <div className="form-group">
                    <label>End Date</label>
                    <input 
                      type="date" 
                      required 
                      value={newTraining.end_time} 
                      onChange={e => setNewTraining({...newTraining, end_time: e.target.value})} 
                    />
                  </div>
                  <div style={{ gridColumn: '1 / -1', marginTop: '10px' }}>
                    <button type="submit" className="btn btn-primary">Publish Training Course</button>
                  </div>
                </form>
              </div>
            )}

            <div className="section-card">
              <h3>Available Training Programs</h3>
              <div className="table-container">
                <table>
                  <thead>
                    <tr>
                      <th>Course Title</th>
                      <th>Location</th>
                      <th>Prerequisite</th>
                      <th>Duration</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    {trainings.map((t) => (
                      <tr key={t.id}>
                        <td><strong>{t.name}</strong></td>
                        <td>{t.place}, {t.country}</td>
                        <td>{t.qualification} (Age: {t.age_requirement}+)</td>
                        <td>{t.start_time} to {t.end_time}</td>
                        <td>
                          <button 
                            className="btn btn-primary" 
                            style={{ fontSize: '0.8rem', padding: '8px 14px' }}
                            onClick={() => handleEnrollStaff(1, t.id)}
                          >
                            Apply / Register
                          </button>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        )}

        {/* Departments View */}
        {activeTab === 'departments' && currentUser.role === 'Admin' && (
          <div className="section-card">
            <h3>Ministry Departments</h3>
            <div className="table-container">
              <table>
                <thead>
                  <tr>
                    <th>Department ID</th>
                    <th>Department Name</th>
                    <th>Assigned Manager</th>
                  </tr>
                </thead>
                <tbody>
                  {departments.map((d) => (
                    <tr key={d.id}>
                      <td>#{d.id}</td>
                      <td><strong>{d.name}</strong></td>
                      <td>{d.manager}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* Reports View */}
        {activeTab === 'reports' && (
          <div className="section-card">
            <h3>Training Record & History Reports</h3>
            <p style={{ color: '#64748b', marginBottom: '20px' }}>
              Centralized registry log preventing duplicate training nominations across departments.
            </p>
            <div className="table-container">
              <table>
                <thead>
                  <tr>
                    <th>Staff Name</th>
                    <th>Training Program</th>
                    <th>Enrollment Date</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  {enrollments.map((e) => {
                    const s = staffList.find(st => st.id === e.staff_id);
                    const t = trainings.find(tr => tr.id === e.training_id);
                    return (
                      <tr key={e.id}>
                        <td><strong>{s ? s.full_name : 'N/A'}</strong></td>
                        <td>{t ? t.name : 'N/A'}</td>
                        <td>{e.enrollment_date}</td>
                        <td>
                          <span className={`status-badge ${e.status === 'approved' ? 'status-approved' : 'status-pending'}`}>
                            {e.status}
                          </span>
                        </td>
                      </tr>
                    );
                  })}
                </tbody>
              </table>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}